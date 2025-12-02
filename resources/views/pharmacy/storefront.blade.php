@extends('pharmacy.layout')

@section('title', 'My Storefront')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <?php include app_path('doctor_storefront.php'); ?>
</div>
@endsection
