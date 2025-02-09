@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add Products</h2>
    <form action="{{ route('shop.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="text" name="name" placeholder="Product Name" required>
        <input type="number" name="price" placeholder="Price" required>
        <input type="file" name="image" accept="image/*" required>
        <button type="submit">Add Product</button>
    </form>

    <h2>Your Products</h2>
    @foreach ($products as $product)
        <p><strong>{{ $product->name }}</strong>: ${{ $product->price }}</p>
        <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" style="max-width: 100px;">
    @endforeach
</div>
@endsection
