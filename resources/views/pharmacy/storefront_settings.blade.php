@extends('pharmacy.layout')

@section('title', 'Storefront Settings')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <?php include app_path('doctor_storefront_settings.php'); ?>
</div>
@endsection
