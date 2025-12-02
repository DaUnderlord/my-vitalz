# 📹 VIDEO CALL IMPLEMENTATION GUIDE - FREE OPTIONS

**Date:** November 12, 2025  
**For:** MyVitalz Doctor-Patient Video Consultations

---

## 🆓 FREE VIDEO CALL OPTIONS

### **Option 1: WebRTC (100% Free, Self-Hosted)** ⭐ RECOMMENDED
**Cost:** FREE  
**Pros:**
- No third-party dependencies
- Peer-to-peer (P2P) connection
- Low latency
- Full control
- No API limits
- HIPAA-compliant (with proper setup)

**Cons:**
- Requires STUN/TURN server for NAT traversal
- More complex implementation
- Need to handle signaling

**Implementation:**
```javascript
// Simple WebRTC implementation
const peerConnection = new RTCPeerConnection({
    iceServers: [
        { urls: 'stun:stun.l.google.com:19302' }, // Free Google STUN
        { urls: 'stun:stun1.l.google.com:19302' }
    ]
});

// Get user media
navigator.mediaDevices.getUserMedia({ video: true, audio: true })
    .then(stream => {
        localVideo.srcObject = stream;
        stream.getTracks().forEach(track => {
            peerConnection.addTrack(track, stream);
        });
    });

// Handle incoming stream
peerConnection.ontrack = event => {
    remoteVideo.srcObject = event.streams[0];
};
```

**Free STUN Servers:**
- Google: `stun:stun.l.google.com:19302`
- OpenRelay: `stun:openrelay.metered.ca:80`

**Free TURN Servers (for NAT traversal):**
- Metered.ca: https://www.metered.ca/tools/openrelay/ (Free tier: 50GB/month)
- Xirsys: https://xirsys.com/ (Free tier available)

---

### **Option 2: Daily.co (Free Tier)** ⭐ EASIEST
**Cost:** FREE up to 10,000 minutes/month  
**Pros:**
- Super easy integration (5 minutes)
- Handles all WebRTC complexity
- Built-in UI components
- Recording available
- Screen sharing
- Mobile support
- HIPAA-compliant (paid tier)

**Cons:**
- 10,000 min/month limit on free tier
- Requires internet connection
- Third-party dependency

**Implementation:**
```html
<!-- Include Daily.co SDK -->
<script src="https://unpkg.com/@daily-co/daily-js"></script>

<script>
// Create a room
async function createVideoRoom() {
    const response = await fetch('https://api.daily.co/v1/rooms', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer YOUR_API_KEY'
        },
        body: JSON.stringify({
            name: 'appointment-' + appointmentId,
            privacy: 'private',
            properties: {
                max_participants: 2,
                enable_screenshare: true,
                enable_chat: true
            }
        })
    });
    const room = await response.json();
    return room.url;
}

// Join a room
const callFrame = window.DailyIframe.createFrame({
    iframeStyle: {
        position: 'fixed',
        width: '100%',
        height: '100%',
        top: 0,
        left: 0
    }
});

callFrame.join({ url: roomUrl });
</script>
```

**Sign up:** https://www.daily.co/  
**Free Tier:** 10,000 minutes/month, unlimited rooms

---

### **Option 3: Jitsi Meet (100% Free, Open Source)** ⭐ BEST FOR PRIVACY
**Cost:** FREE (unlimited)  
**Pros:**
- Completely free
- Open source
- Can self-host
- No account needed
- End-to-end encryption
- Screen sharing
- Recording (self-hosted)

**Cons:**
- Public Jitsi server can be unreliable
- Self-hosting requires server
- Less polished UI

**Implementation:**
```html
<!-- Include Jitsi API -->
<script src='https://meet.jit.si/external_api.js'></script>

<div id="jitsi-container" style="height: 600px;"></div>

<script>
const domain = 'meet.jit.si'; // Or your self-hosted domain
const options = {
    roomName: 'MyVitalzConsultation' + appointmentId,
    width: '100%',
    height: 600,
    parentNode: document.querySelector('#jitsi-container'),
    configOverride: {
        startWithAudioMuted: false,
        startWithVideoMuted: false,
        enableWelcomePage: false
    },
    interfaceConfigOverride: {
        TOOLBAR_BUTTONS: [
            'microphone', 'camera', 'closedcaptions', 'desktop',
            'fullscreen', 'hangup', 'chat', 'settings', 'videoquality'
        ],
        SHOW_JITSI_WATERMARK: false
    },
    userInfo: {
        displayName: doctorName
    }
};

const api = new JitsiMeetExternalAPI(domain, options);

// Events
api.addEventListener('videoConferenceJoined', () => {
    console.log('Doctor joined the call');
});

api.addEventListener('videoConferenceLeft', () => {
    console.log('Call ended');
});
</script>
```

**Public Server:** https://meet.jit.si (Free, unlimited)  
**Self-Host Guide:** https://jitsi.github.io/handbook/docs/devops-guide/

---

### **Option 4: Agora.io (Free Tier)**
**Cost:** FREE up to 10,000 minutes/month  
**Pros:**
- High quality
- Global CDN
- Good documentation
- Mobile SDKs
- Recording

**Cons:**
- Requires API key
- 10,000 min/month limit
- More complex than Daily.co

**Implementation:**
```html
<script src="https://download.agora.io/sdk/release/AgoraRTC_N.js"></script>

<script>
const client = AgoraRTC.createClient({ mode: "rtc", codec: "vp8" });

async function startCall() {
    await client.join(APP_ID, channelName, token, uid);
    
    const localAudioTrack = await AgoraRTC.createMicrophoneAudioTrack();
    const localVideoTrack = await AgoraRTC.createCameraVideoTrack();
    
    await client.publish([localAudioTrack, localVideoTrack]);
    
    localVideoTrack.play('local-video');
}

client.on("user-published", async (user, mediaType) => {
    await client.subscribe(user, mediaType);
    if (mediaType === "video") {
        user.videoTrack.play('remote-video');
    }
    if (mediaType === "audio") {
        user.audioTrack.play();
    }
});
</script>
```

**Sign up:** https://www.agora.io/  
**Free Tier:** 10,000 minutes/month

---

## 🏆 RECOMMENDED SOLUTION FOR MYVITALZ

### **Best Choice: Daily.co (Free Tier)**

**Why:**
1. ✅ Easiest to implement (5-10 minutes)
2. ✅ 10,000 minutes/month = ~166 hours = plenty for testing
3. ✅ Professional UI out of the box
4. ✅ Reliable infrastructure
5. ✅ Can upgrade to HIPAA-compliant paid tier later
6. ✅ Built-in recording, screen sharing, chat

**Implementation Steps:**

### **Step 1: Sign Up**
1. Go to https://www.daily.co/
2. Sign up for free account
3. Get your API key from dashboard

### **Step 2: Create Database Table**
```sql
CREATE TABLE IF NOT EXISTS video_consultations (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    appointment_id BIGINT,
    doctor_id BIGINT,
    patient_id BIGINT,
    room_name VARCHAR(255),
    room_url VARCHAR(500),
    status VARCHAR(50) DEFAULT 'scheduled',
    started_at TIMESTAMP NULL,
    ended_at TIMESTAMP NULL,
    duration_minutes INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX(appointment_id),
    INDEX(doctor_id),
    INDEX(patient_id)
);
```

### **Step 3: Add to PharmacyController**
```php
public function createVideoRoom(Request $request)
{
    $user = $this->checkAuth($request);
    $appointment_id = (int)$request->input('appointment_id');
    
    // Verify appointment belongs to this doctor
    $appointment = DB::select('
        SELECT * FROM appointments 
        WHERE id = ? AND doctor = ?
    ', [$appointment_id, $user->id]);
    
    if(empty($appointment)) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }
    
    $appointment = $appointment[0];
    
    // Create Daily.co room
    $room_name = 'consultation-' . $appointment_id . '-' . time();
    
    $ch = curl_init('https://api.daily.co/v1/rooms');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer YOUR_DAILY_API_KEY'
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'name' => $room_name,
        'privacy' => 'private',
        'properties' => [
            'max_participants' => 2,
            'enable_screenshare' => true,
            'enable_chat' => true,
            'enable_recording' => 'cloud', // Optional
            'exp' => time() + 3600 // Expires in 1 hour
        ]
    ]));
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    $room = json_decode($response);
    
    // Save to database
    DB::insert('
        INSERT INTO video_consultations 
        (appointment_id, doctor_id, patient_id, room_name, room_url, status, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ', [
        $appointment_id,
        $user->id,
        $appointment->user,
        $room_name,
        $room->url,
        'scheduled',
        date('Y-m-d H:i:s'),
        date('Y-m-d H:i:s')
    ]);
    
    // Send notification to patient
    DB::insert('
        INSERT INTO notifications (user_id, title, message, seen, date, created_at, updated_at)
        VALUES (?, ?, ?, 0, ?, ?, ?)
    ', [
        $appointment->user,
        'Video Consultation Ready',
        'Your doctor has started a video consultation. Click to join: ' . $room->url,
        date('d-M-Y'),
        date('Y-m-d H:i:s'),
        date('Y-m-d H:i:s')
    ]);
    
    return response()->json([
        'success' => true,
        'room_url' => $room->url
    ]);
}
```

### **Step 4: Add Button to Appointment Modal**
```html
<!-- In appointment modal, add this button -->
<button type="button" class="btn btn-success" onclick="startVideoCall(appointmentId)">
    <i class="bx bx-video me-1"></i> Start Video Call
</button>

<script>
async function startVideoCall(appointmentId) {
    const response = await fetch('/dashboard-pharmacy?pg=create-video-room', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ appointment_id: appointmentId })
    });
    
    const data = await response.json();
    
    if(data.success) {
        // Open video call in new window
        window.open(data.room_url, 'VideoCall', 'width=1200,height=800');
    }
}
</script>
```

### **Step 5: Embedded Video Call (Optional)**
```html
<!-- Create a dedicated video call page -->
<div id="video-container" style="height: 100vh;"></div>

<script src="https://unpkg.com/@daily-co/daily-js"></script>
<script>
const roomUrl = '<?php echo $room_url; ?>';

const callFrame = window.DailyIframe.createFrame({
    showLeaveButton: true,
    iframeStyle: {
        position: 'fixed',
        width: '100%',
        height: '100%',
        top: 0,
        left: 0,
        border: 0
    }
});

callFrame.join({ url: roomUrl });

// Track call duration
callFrame.on('joined-meeting', () => {
    console.log('Call started');
    // Update database: started_at
});

callFrame.on('left-meeting', () => {
    console.log('Call ended');
    // Update database: ended_at, duration
    window.close();
});
</script>
```

---

## 💰 COST COMPARISON

| Solution | Free Tier | Paid Tier | Best For |
|----------|-----------|-----------|----------|
| **Daily.co** | 10,000 min/month | $0.0015/min | Easy implementation |
| **Jitsi Meet** | Unlimited (public) | Self-host costs | Privacy, unlimited |
| **WebRTC** | Unlimited | Server costs | Full control |
| **Agora.io** | 10,000 min/month | $0.99/1000 min | High quality |

---

## 🎯 RECOMMENDATION

**For MyVitalz, use Daily.co because:**
1. ✅ FREE for testing (10,000 min = ~166 hours)
2. ✅ 5-minute integration
3. ✅ Professional quality
4. ✅ Can scale to HIPAA-compliant paid tier
5. ✅ Built-in recording for medical records

**Monthly Usage Estimate:**
- 100 consultations/month × 15 min avg = 1,500 minutes
- Well within 10,000 minute free tier
- Cost if exceeded: ~$2.25/month for 1,500 extra minutes

---

## 🚀 QUICK START (5 Minutes)

1. Sign up at https://www.daily.co/
2. Get API key
3. Add `createVideoRoom()` method to PharmacyController
4. Run migration for `video_consultations` table
5. Add "Start Video Call" button to appointments
6. Test!

**That's it! You'll have professional video consultations working in 5 minutes.** 📹✅

---

## 📞 SUPPORT

- Daily.co Docs: https://docs.daily.co/
- Jitsi Docs: https://jitsi.github.io/handbook/
- WebRTC Guide: https://webrtc.org/getting-started/overview

**Need help implementing? Let me know!** 🚀
