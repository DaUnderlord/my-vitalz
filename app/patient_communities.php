<?php
/**
 * Patient Communities - Full-featured health support communities
 * Features: Browse, Join, Post, Comment, Like, Search
 */

// Get patient ID from user array passed by controller
$uid = $user[0]->id;

// Handle community actions
$action = isset($_GET['action']) ? $_GET['action'] : '';
$community_slug = isset($_GET['community']) ? $_GET['community'] : '';
$post_id = isset($_GET['post']) ? intval($_GET['post']) : 0;

// Handle POST actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_action = isset($_POST['community_action']) ? $_POST['community_action'] : '';
    
    // Join community
    if ($post_action === 'join') {
        $community_id = intval($_POST['community_id']);
        $exists = DB::select('SELECT id FROM community_members WHERE community_id = ? AND user_id = ?', [$community_id, $uid]);
        if (empty($exists)) {
            DB::insert('INSERT INTO community_members (community_id, user_id, role, joined_at, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?)', 
                [$community_id, $uid, 'member', date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);
        }
        header('Location: ?pg=communities&community=' . $_POST['community_slug']);
        exit;
    }
    
    // Leave community
    if ($post_action === 'leave') {
        $community_id = intval($_POST['community_id']);
        DB::delete('DELETE FROM community_members WHERE community_id = ? AND user_id = ?', [$community_id, $uid]);
        header('Location: ?pg=communities');
        exit;
    }
    
    // Create post
    if ($post_action === 'create_post') {
        $community_id = intval($_POST['community_id']);
        $title = trim($_POST['post_title'] ?? '');
        $content = trim($_POST['post_content']);
        $post_type = $_POST['post_type'] ?? 'discussion';
        
        if (!empty($content)) {
            DB::insert('INSERT INTO community_posts (community_id, user_id, title, content, post_type, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?)',
                [$community_id, $uid, $title, $content, $post_type, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);
        }
        header('Location: ?pg=communities&community=' . $_POST['community_slug']);
        exit;
    }
    
    // Create comment
    if ($post_action === 'create_comment') {
        $post_id = intval($_POST['post_id']);
        $content = trim($_POST['comment_content']);
        $parent_id = !empty($_POST['parent_id']) ? intval($_POST['parent_id']) : null;
        
        if (!empty($content)) {
            DB::insert('INSERT INTO community_comments (post_id, user_id, parent_id, content, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?)',
                [$post_id, $uid, $parent_id, $content, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);
            
            // Update comment count
            DB::update('UPDATE community_posts SET comments_count = comments_count + 1 WHERE id = ?', [$post_id]);
        }
        header('Location: ?pg=communities&community=' . $_POST['community_slug'] . '&post=' . $post_id);
        exit;
    }
    
    // Like/unlike post
    if ($post_action === 'toggle_like') {
        $likeable_type = $_POST['likeable_type'];
        $likeable_id = intval($_POST['likeable_id']);
        
        $exists = DB::select('SELECT id FROM community_likes WHERE user_id = ? AND likeable_type = ? AND likeable_id = ?', 
            [$uid, $likeable_type, $likeable_id]);
        
        if (!empty($exists)) {
            DB::delete('DELETE FROM community_likes WHERE user_id = ? AND likeable_type = ? AND likeable_id = ?', 
                [$uid, $likeable_type, $likeable_id]);
            if ($likeable_type === 'post') {
                DB::update('UPDATE community_posts SET likes_count = GREATEST(likes_count - 1, 0) WHERE id = ?', [$likeable_id]);
            } else {
                DB::update('UPDATE community_comments SET likes_count = GREATEST(likes_count - 1, 0) WHERE id = ?', [$likeable_id]);
            }
        } else {
            DB::insert('INSERT INTO community_likes (user_id, likeable_type, likeable_id, created_at, updated_at) VALUES (?, ?, ?, ?, ?)',
                [$uid, $likeable_type, $likeable_id, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);
            if ($likeable_type === 'post') {
                DB::update('UPDATE community_posts SET likes_count = likes_count + 1 WHERE id = ?', [$likeable_id]);
            } else {
                DB::update('UPDATE community_comments SET likes_count = likes_count + 1 WHERE id = ?', [$likeable_id]);
            }
        }
        
        // Return JSON for AJAX
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            echo json_encode(['success' => true]);
            exit;
        }
        
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
}

// Get user's joined communities
$my_communities = DB::select('
    SELECT c.*, cm.role, cm.joined_at,
        (SELECT COUNT(*) FROM community_members WHERE community_id = c.id) as member_count
    FROM communities c
    INNER JOIN community_members cm ON c.id = cm.community_id
    WHERE cm.user_id = ? AND c.is_active = 1
    ORDER BY cm.joined_at DESC
', [$uid]);

$my_community_ids = array_map(function($c) { return $c->id; }, $my_communities);

// Get all public communities
$all_communities = DB::select('
    SELECT c.*,
        (SELECT COUNT(*) FROM community_members WHERE community_id = c.id) as member_count,
        (SELECT COUNT(*) FROM community_posts WHERE community_id = c.id) as post_count
    FROM communities c
    WHERE c.is_active = 1 AND c.is_public = 1
    ORDER BY c.is_featured DESC, member_count DESC
');

// Get featured communities
$featured_communities = array_filter($all_communities, function($c) { return $c->is_featured; });

// Category filter
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';
$search_query = isset($_GET['q']) ? trim($_GET['q']) : '';

// View modes: list, community, post
$view_mode = 'list';
$current_community = null;
$current_post = null;
$community_posts = [];
$post_comments = [];

if ($community_slug) {
    $view_mode = 'community';
    $current_community = DB::select('SELECT * FROM communities WHERE slug = ? AND is_active = 1', [$community_slug]);
    $current_community = !empty($current_community) ? $current_community[0] : null;
    
    if ($current_community) {
        // Check if user is member
        $is_member = in_array($current_community->id, $my_community_ids);
        
        // Get community posts
        $community_posts = DB::select('
            SELECT cp.*, u.first_name, u.last_name, u.photo,
                (SELECT COUNT(*) FROM community_likes WHERE likeable_type = "post" AND likeable_id = cp.id AND user_id = ?) as user_liked
            FROM community_posts cp
            INNER JOIN users u ON cp.user_id = u.id
            WHERE cp.community_id = ? AND cp.is_approved = 1 AND cp.deleted_at IS NULL
            ORDER BY cp.is_pinned DESC, cp.created_at DESC
            LIMIT 50
        ', [$uid, $current_community->id]);
        
        // Get member count
        $member_count = DB::select('SELECT COUNT(*) as count FROM community_members WHERE community_id = ?', [$current_community->id]);
        $current_community->member_count = $member_count[0]->count ?? 0;
        
        // If viewing specific post
        if ($post_id) {
            $view_mode = 'post';
            $current_post = DB::select('
                SELECT cp.*, u.first_name, u.last_name, u.photo,
                    (SELECT COUNT(*) FROM community_likes WHERE likeable_type = "post" AND likeable_id = cp.id AND user_id = ?) as user_liked
                FROM community_posts cp
                INNER JOIN users u ON cp.user_id = u.id
                WHERE cp.id = ? AND cp.deleted_at IS NULL
            ', [$uid, $post_id]);
            $current_post = !empty($current_post) ? $current_post[0] : null;
            
            if ($current_post) {
                // Increment view count
                DB::update('UPDATE community_posts SET views_count = views_count + 1 WHERE id = ?', [$post_id]);
                
                // Get comments
                $post_comments = DB::select('
                    SELECT cc.*, u.first_name, u.last_name, u.photo,
                        (SELECT COUNT(*) FROM community_likes WHERE likeable_type = "comment" AND likeable_id = cc.id AND user_id = ?) as user_liked
                    FROM community_comments cc
                    INNER JOIN users u ON cc.user_id = u.id
                    WHERE cc.post_id = ? AND cc.is_approved = 1 AND cc.deleted_at IS NULL
                    ORDER BY cc.created_at ASC
                ', [$uid, $post_id]);
            }
        }
    }
}

// Helper function to format time ago
function timeAgo($datetime) {
    $time = strtotime($datetime);
    $diff = time() - $time;
    
    if ($diff < 60) return 'just now';
    if ($diff < 3600) return floor($diff / 60) . 'm ago';
    if ($diff < 86400) return floor($diff / 3600) . 'h ago';
    if ($diff < 604800) return floor($diff / 86400) . 'd ago';
    return date('M j', $time);
}

// Get categories for filter
$categories = [
    'diabetes' => 'Diabetes',
    'cardiovascular' => 'Heart Health',
    'mental_health' => 'Mental Health',
    'fitness' => 'Fitness',
    'nutrition' => 'Nutrition',
    'weight' => 'Weight Management',
    'chronic_pain' => 'Chronic Pain',
    'womens_health' => "Women's Health",
    'senior' => 'Senior Health',
    'sleep' => 'Sleep'
];
?>

<style>
.community-card {
    transition: all 0.3s ease;
    border: 1px solid #e4e7ec;
    border-radius: 12px;
}
.community-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 30px rgba(105,108,255,0.15);
    border-color: rgba(105,108,255,0.3);
}
.community-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}
.post-card {
    border-left: 3px solid transparent;
    transition: all 0.2s ease;
}
.post-card:hover {
    background: #f8f9fa;
    border-left-color: var(--bs-primary);
}
.post-type-badge {
    font-size: 0.7rem;
    padding: 2px 8px;
}
.comment-item {
    border-left: 2px solid #e9ecef;
    padding-left: 12px;
    margin-left: 0;
}
.comment-item.reply {
    margin-left: 40px;
}
.like-btn {
    cursor: pointer;
    transition: all 0.2s ease;
}
.like-btn:hover {
    transform: scale(1.1);
}
.like-btn.liked {
    color: #e74c3c;
}
.avatar-sm {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
}
.avatar-md {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}
.rules-list {
    list-style: none;
    padding: 0;
}
.rules-list li {
    padding: 8px 0;
    border-bottom: 1px solid #f0f0f0;
}
.rules-list li:last-child {
    border-bottom: none;
}
</style>

<?php if ($view_mode === 'list'): ?>
<!-- COMMUNITIES LIST VIEW -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-1">Health Communities</h5>
                <p class="text-muted mb-0">Connect with others on similar health journeys</p>
            </div>
        </div>
    </div>
</div>

<!-- My Communities -->
<?php if (!empty($my_communities)): ?>
<div class="row mb-4">
    <div class="col-12">
        <h6 class="text-muted mb-3"><i class="bx bx-check-circle me-1"></i> My Communities (<?php echo count($my_communities); ?>)</h6>
        <div class="row">
            <?php foreach (array_slice($my_communities, 0, 4) as $community): ?>
            <div class="col-md-3 col-sm-6 mb-3">
                <a href="?pg=communities&community=<?php echo $community->slug; ?>" class="text-decoration-none">
                    <div class="card community-card h-100" style="border-top: 3px solid <?php echo $community->primary_color; ?>;">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center mb-2">
                                <div class="community-icon me-2" style="background: <?php echo $community->primary_color; ?>; width: 40px; height: 40px; font-size: 1rem;">
                                    <i class="bx <?php echo $community->icon ?: 'bx-group'; ?>"></i>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <h6 class="mb-0 text-truncate"><?php echo htmlspecialchars($community->name); ?></h6>
                                    <small class="text-muted"><?php echo $community->member_count; ?> members</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Search & Filter -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body py-3">
                <form method="GET" class="row g-2 align-items-center">
                    <input type="hidden" name="pg" value="communities">
                    <div class="col-md-5">
                        <input type="text" class="form-control form-control-sm" name="q" value="<?php echo htmlspecialchars($search_query); ?>" placeholder="Search communities...">
                    </div>
                    <div class="col-md-4">
                        <select class="form-select form-select-sm" name="category">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $key => $label): ?>
                            <option value="<?php echo $key; ?>" <?php echo $category_filter === $key ? 'selected' : ''; ?>><?php echo $label; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bx bx-search me-1"></i> Search</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Featured Communities -->
<?php if (!empty($featured_communities) && empty($search_query) && empty($category_filter)): ?>
<div class="row mb-4">
    <div class="col-12">
        <h6 class="text-muted mb-3"><i class="bx bx-star me-1"></i> Featured Communities</h6>
    </div>
    <?php foreach ($featured_communities as $community): 
        $is_joined = in_array($community->id, $my_community_ids);
    ?>
    <div class="col-lg-6 mb-3">
        <div class="card community-card h-100">
            <div class="card-body">
                <div class="d-flex">
                    <div class="community-icon me-3" style="background: <?php echo $community->primary_color; ?>;">
                        <i class="bx <?php echo $community->icon ?: 'bx-group'; ?>"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h6 class="mb-1"><?php echo htmlspecialchars($community->name); ?></h6>
                                <div class="d-flex gap-3 text-muted small">
                                    <span><i class="bx bx-user me-1"></i><?php echo $community->member_count; ?> members</span>
                                    <span><i class="bx bx-message-detail me-1"></i><?php echo $community->post_count; ?> posts</span>
                                </div>
                            </div>
                            <?php if ($is_joined): ?>
                                <span class="badge bg-success"><i class="bx bx-check me-1"></i>Joined</span>
                            <?php endif; ?>
                        </div>
                        <p class="text-muted small mb-3"><?php echo htmlspecialchars(substr($community->description, 0, 120)); ?>...</p>
                        <a href="?pg=communities&community=<?php echo $community->slug; ?>" class="btn btn-sm" style="background: <?php echo $community->primary_color; ?>; color: white;">
                            <?php echo $is_joined ? 'View Community' : 'Join Community'; ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- All Communities -->
<div class="row">
    <div class="col-12">
        <h6 class="text-muted mb-3"><i class="bx bx-grid-alt me-1"></i> <?php echo $search_query || $category_filter ? 'Search Results' : 'All Communities'; ?></h6>
    </div>
    <?php 
    $filtered_communities = $all_communities;
    if ($category_filter) {
        $filtered_communities = array_filter($filtered_communities, function($c) use ($category_filter) {
            return $c->category === $category_filter;
        });
    }
    if ($search_query) {
        $filtered_communities = array_filter($filtered_communities, function($c) use ($search_query) {
            return stripos($c->name, $search_query) !== false || stripos($c->description, $search_query) !== false;
        });
    }
    ?>
    <?php if (empty($filtered_communities)): ?>
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bx bx-search bx-lg text-muted mb-3"></i>
                <h5 class="text-muted">No Communities Found</h5>
                <p class="text-muted">Try adjusting your search or filters</p>
            </div>
        </div>
    </div>
    <?php else: ?>
    <?php foreach ($filtered_communities as $community): 
        $is_joined = in_array($community->id, $my_community_ids);
    ?>
    <div class="col-lg-4 col-md-6 mb-3">
        <div class="card community-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-start mb-3">
                    <div class="community-icon me-3" style="background: <?php echo $community->primary_color; ?>;">
                        <i class="bx <?php echo $community->icon ?: 'bx-group'; ?>"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1"><?php echo htmlspecialchars($community->name); ?></h6>
                        <span class="badge bg-label-secondary"><?php echo $categories[$community->category] ?? $community->category; ?></span>
                    </div>
                    <?php if ($is_joined): ?>
                        <span class="badge bg-success-subtle text-success"><i class="bx bx-check"></i></span>
                    <?php endif; ?>
                </div>
                <p class="text-muted small mb-3" style="min-height: 40px;"><?php echo htmlspecialchars(substr($community->description, 0, 80)); ?>...</p>
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        <i class="bx bx-user me-1"></i><?php echo $community->member_count; ?> members
                    </small>
                    <a href="?pg=communities&community=<?php echo $community->slug; ?>" class="btn btn-sm btn-outline-primary">
                        View
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php elseif ($view_mode === 'community' && $current_community): ?>
<!-- SINGLE COMMUNITY VIEW -->
<?php $is_member = in_array($current_community->id, $my_community_ids); ?>

<!-- Community Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card" style="border-top: 4px solid <?php echo $current_community->primary_color; ?>;">
            <div class="card-body">
                <div class="d-flex flex-wrap align-items-center">
                    <div class="community-icon me-3 mb-2" style="background: <?php echo $current_community->primary_color; ?>;">
                        <i class="bx <?php echo $current_community->icon ?: 'bx-group'; ?>"></i>
                    </div>
                    <div class="flex-grow-1 mb-2">
                        <h4 class="mb-1"><?php echo htmlspecialchars($current_community->name); ?></h4>
                        <div class="d-flex flex-wrap gap-3 text-muted">
                            <span><i class="bx bx-user me-1"></i><?php echo $current_community->member_count; ?> members</span>
                            <span><i class="bx bx-message-detail me-1"></i><?php echo count($community_posts); ?> posts</span>
                            <span class="badge bg-label-secondary"><?php echo $categories[$current_community->category] ?? $current_community->category; ?></span>
                        </div>
                    </div>
                    <div class="mb-2">
                        <?php if ($is_member): ?>
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                <input type="hidden" name="community_action" value="leave">
                                <input type="hidden" name="community_id" value="<?php echo $current_community->id; ?>">
                                <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Leave this community?')">
                                    <i class="bx bx-log-out me-1"></i> Leave
                                </button>
                            </form>
                        <?php else: ?>
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                <input type="hidden" name="community_action" value="join">
                                <input type="hidden" name="community_id" value="<?php echo $current_community->id; ?>">
                                <input type="hidden" name="community_slug" value="<?php echo $current_community->slug; ?>">
                                <button type="submit" class="btn btn-sm" style="background: <?php echo $current_community->primary_color; ?>; color: white;">
                                    <i class="bx bx-plus me-1"></i> Join Community
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
                <p class="text-muted mb-0 mt-3"><?php echo htmlspecialchars($current_community->description); ?></p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Posts Column -->
    <div class="col-lg-8 mb-4">
        <!-- Create Post (members only) -->
        <?php if ($is_member): ?>
        <div class="card mb-4">
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                    <input type="hidden" name="community_action" value="create_post">
                    <input type="hidden" name="community_id" value="<?php echo $current_community->id; ?>">
                    <input type="hidden" name="community_slug" value="<?php echo $current_community->slug; ?>">
                    
                    <div class="d-flex align-items-start mb-3">
                        <img src="/assets/<?php echo $user[0]->photo ? 'images/'.$user[0]->photo : 'img/avatars/user.png'; ?>" class="avatar-md me-3" alt="">
                        <div class="flex-grow-1">
                            <input type="text" class="form-control form-control-sm mb-2" name="post_title" placeholder="Post title (optional)">
                            <textarea class="form-control" name="post_content" rows="3" placeholder="Share something with the community..." required></textarea>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <select class="form-select form-select-sm" name="post_type" style="width: auto;">
                            <option value="discussion">Discussion</option>
                            <option value="question">Question</option>
                            <option value="resource">Resource</option>
                        </select>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="bx bx-send me-1"></i> Post
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Posts List -->
        <?php if (empty($community_posts)): ?>
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bx bx-message-detail bx-lg text-muted mb-3"></i>
                <h5 class="text-muted">No Posts Yet</h5>
                <p class="text-muted"><?php echo $is_member ? 'Be the first to start a discussion!' : 'Join the community to see and create posts.'; ?></p>
            </div>
        </div>
        <?php else: ?>
        <?php foreach ($community_posts as $post): ?>
        <div class="card post-card mb-3">
            <div class="card-body">
                <div class="d-flex">
                    <img src="/assets/<?php echo $post->photo ? 'images/'.$post->photo : 'img/avatars/user.png'; ?>" class="avatar-md me-3" alt="">
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <strong><?php echo htmlspecialchars($post->first_name . ' ' . $post->last_name); ?></strong>
                                <span class="text-muted small ms-2"><?php echo timeAgo($post->created_at); ?></span>
                                <?php if ($post->is_pinned): ?>
                                    <span class="badge bg-warning ms-2"><i class="bx bx-pin"></i> Pinned</span>
                                <?php endif; ?>
                            </div>
                            <span class="badge post-type-badge bg-label-<?php 
                                echo $post->post_type === 'question' ? 'info' : 
                                    ($post->post_type === 'announcement' ? 'warning' : 
                                    ($post->post_type === 'resource' ? 'success' : 'secondary')); 
                            ?>"><?php echo ucfirst($post->post_type); ?></span>
                        </div>
                        
                        <?php if ($post->title): ?>
                        <h6 class="mb-2"><?php echo htmlspecialchars($post->title); ?></h6>
                        <?php endif; ?>
                        
                        <p class="mb-3"><?php echo nl2br(htmlspecialchars(substr($post->content, 0, 300))); ?><?php echo strlen($post->content) > 300 ? '...' : ''; ?></p>
                        
                        <div class="d-flex align-items-center gap-3">
                            <form method="POST" class="d-inline like-form">
                                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                <input type="hidden" name="community_action" value="toggle_like">
                                <input type="hidden" name="likeable_type" value="post">
                                <input type="hidden" name="likeable_id" value="<?php echo $post->id; ?>">
                                <button type="submit" class="btn btn-sm btn-link p-0 like-btn <?php echo $post->user_liked ? 'liked' : ''; ?>">
                                    <i class="bx <?php echo $post->user_liked ? 'bxs-heart' : 'bx-heart'; ?> me-1"></i>
                                    <span><?php echo $post->likes_count; ?></span>
                                </button>
                            </form>
                            <a href="?pg=communities&community=<?php echo $current_community->slug; ?>&post=<?php echo $post->id; ?>" class="btn btn-sm btn-link p-0 text-muted">
                                <i class="bx bx-comment me-1"></i> <?php echo $post->comments_count; ?> comments
                            </a>
                            <span class="text-muted small"><i class="bx bx-show me-1"></i><?php echo $post->views_count; ?> views</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Community Rules -->
        <?php if ($current_community->rules): ?>
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="bx bx-shield me-1"></i> Community Guidelines</h6>
            </div>
            <div class="card-body">
                <ul class="rules-list mb-0">
                    <?php foreach (explode("\n", $current_community->rules) as $rule): ?>
                    <li class="small"><?php echo htmlspecialchars($rule); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Back to Communities -->
        <div class="card">
            <div class="card-body">
                <a href="?pg=communities" class="btn btn-outline-secondary w-100">
                    <i class="bx bx-arrow-back me-1"></i> Back to Communities
                </a>
            </div>
        </div>
    </div>
</div>

<?php elseif ($view_mode === 'post' && $current_post && $current_community): ?>
<!-- SINGLE POST VIEW -->
<?php $is_member = in_array($current_community->id, $my_community_ids); ?>

<!-- Breadcrumb -->
<div class="row mb-3">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="?pg=communities">Communities</a></li>
                <li class="breadcrumb-item"><a href="?pg=communities&community=<?php echo $current_community->slug; ?>"><?php echo htmlspecialchars($current_community->name); ?></a></li>
                <li class="breadcrumb-item active">Post</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Post -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex mb-3">
                    <img src="/assets/<?php echo $current_post->photo ? 'images/'.$current_post->photo : 'img/avatars/user.png'; ?>" class="avatar-md me-3" alt="">
                    <div>
                        <strong><?php echo htmlspecialchars($current_post->first_name . ' ' . $current_post->last_name); ?></strong>
                        <div class="text-muted small"><?php echo timeAgo($current_post->created_at); ?></div>
                    </div>
                </div>
                
                <?php if ($current_post->title): ?>
                <h5 class="mb-3"><?php echo htmlspecialchars($current_post->title); ?></h5>
                <?php endif; ?>
                
                <div class="mb-4" style="white-space: pre-wrap;"><?php echo nl2br(htmlspecialchars($current_post->content)); ?></div>
                
                <div class="d-flex align-items-center gap-3 pt-3 border-top">
                    <form method="POST" class="d-inline">
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                        <input type="hidden" name="community_action" value="toggle_like">
                        <input type="hidden" name="likeable_type" value="post">
                        <input type="hidden" name="likeable_id" value="<?php echo $current_post->id; ?>">
                        <button type="submit" class="btn btn-sm <?php echo $current_post->user_liked ? 'btn-danger' : 'btn-outline-danger'; ?>">
                            <i class="bx <?php echo $current_post->user_liked ? 'bxs-heart' : 'bx-heart'; ?> me-1"></i>
                            <?php echo $current_post->likes_count; ?> Likes
                        </button>
                    </form>
                    <span class="text-muted"><i class="bx bx-comment me-1"></i><?php echo $current_post->comments_count; ?> Comments</span>
                    <span class="text-muted"><i class="bx bx-show me-1"></i><?php echo $current_post->views_count; ?> Views</span>
                </div>
            </div>
        </div>
        
        <!-- Comments -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Comments (<?php echo count($post_comments); ?>)</h6>
            </div>
            <div class="card-body">
                <!-- Add Comment (members only) -->
                <?php if ($is_member): ?>
                <form method="POST" class="mb-4">
                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                    <input type="hidden" name="community_action" value="create_comment">
                    <input type="hidden" name="post_id" value="<?php echo $current_post->id; ?>">
                    <input type="hidden" name="community_slug" value="<?php echo $current_community->slug; ?>">
                    <div class="d-flex">
                        <img src="/assets/<?php echo $user[0]->photo ? 'images/'.$user[0]->photo : 'img/avatars/user.png'; ?>" class="avatar-sm me-2" alt="">
                        <div class="flex-grow-1">
                            <textarea class="form-control form-control-sm" name="comment_content" rows="2" placeholder="Write a comment..." required></textarea>
                            <button type="submit" class="btn btn-primary btn-sm mt-2">Post Comment</button>
                        </div>
                    </div>
                </form>
                <?php endif; ?>
                
                <!-- Comments List -->
                <?php if (empty($post_comments)): ?>
                <p class="text-muted text-center mb-0">No comments yet. <?php echo $is_member ? 'Be the first to comment!' : ''; ?></p>
                <?php else: ?>
                <?php foreach ($post_comments as $comment): ?>
                <div class="comment-item mb-3 <?php echo $comment->parent_id ? 'reply' : ''; ?>">
                    <div class="d-flex">
                        <img src="/assets/<?php echo $comment->photo ? 'images/'.$comment->photo : 'img/avatars/user.png'; ?>" class="avatar-sm me-2" alt="">
                        <div class="flex-grow-1">
                            <div class="bg-light rounded p-2">
                                <strong class="small"><?php echo htmlspecialchars($comment->first_name . ' ' . $comment->last_name); ?></strong>
                                <p class="mb-0 small"><?php echo nl2br(htmlspecialchars($comment->content)); ?></p>
                            </div>
                            <div class="d-flex align-items-center gap-3 mt-1">
                                <small class="text-muted"><?php echo timeAgo($comment->created_at); ?></small>
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                    <input type="hidden" name="community_action" value="toggle_like">
                                    <input type="hidden" name="likeable_type" value="comment">
                                    <input type="hidden" name="likeable_id" value="<?php echo $comment->id; ?>">
                                    <button type="submit" class="btn btn-link btn-sm p-0 text-muted like-btn <?php echo $comment->user_liked ? 'liked' : ''; ?>">
                                        <i class="bx <?php echo $comment->user_liked ? 'bxs-heart' : 'bx-heart'; ?>"></i> <?php echo $comment->likes_count; ?>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Back to Community -->
        <div class="card">
            <div class="card-body">
                <a href="?pg=communities&community=<?php echo $current_community->slug; ?>" class="btn btn-outline-primary w-100">
                    <i class="bx bx-arrow-back me-1"></i> Back to <?php echo htmlspecialchars($current_community->name); ?>
                </a>
            </div>
        </div>
    </div>
</div>

<?php endif; ?>
