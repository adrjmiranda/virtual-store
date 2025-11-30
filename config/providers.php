<?php

use App\Providers\AppServiceProvider;
use App\Providers\ViewServiceProvider;

// Repositories
use App\Repository\AddressRepository;
use App\Repository\AuditLogRepository;
use App\Repository\CartItemRepository;
use App\Repository\CartRepository;
use App\Repository\CategoryRepository;
use App\Repository\CouponRepository;
use App\Repository\EventLogRepository;
use App\Repository\ImageRepository;
use App\Repository\InventoryChangeRepository;
use App\Repository\OrderDiscountRepository;
use App\Repository\OrderItemRepository;
use App\Repository\OrderPaymentRepository;
use App\Repository\OrderRepository;
use App\Repository\PaymentRepository;
use App\Repository\ProductCategoryRepository;
use App\Repository\ProductOptionRepository;
use App\Repository\ProductOptionValueRepository;
use App\Repository\ProductRepository;
use App\Repository\ProductVariantRepository;
use App\Repository\ProductVariantValueRepository;
use App\Repository\UserRepository;

return [
  AppServiceProvider::class,
  ViewServiceProvider::class,

    // Repositories
  AddressRepository::class,
  CartItemRepository::class,
  CategoryRepository::class,
  EventLogRepository::class,
  InventoryChangeRepository::class,
  OrderItemRepository::class,
  OrderRepository::class,
  ProductCategoryRepository::class,
  ProductOptionValueRepository::class,
  ProductVariantRepository::class,
  UserRepository::class,
  AuditLogRepository::class,
  CartRepository::class,
  CouponRepository::class,
  ImageRepository::class,
  OrderDiscountRepository::class,
  OrderPaymentRepository::class,
  PaymentRepository::class,
  ProductOptionRepository::class,
  ProductRepository::class,
  ProductVariantValueRepository::class,
];