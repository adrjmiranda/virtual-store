<?php

namespace App\Providers;

use App\Core\Container;

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

class RepositoriesProvider
{
  public static function register(Container $container): void
  {
    $container->bind(AddressRepository::class, AddressRepository::class);
    $container->bind(CartItemRepository::class, CartItemRepository::class);
    $container->bind(CategoryRepository::class, CategoryRepository::class);
    $container->bind(EventLogRepository::class, EventLogRepository::class);
    $container->bind(InventoryChangeRepository::class, InventoryChangeRepository::class);
    $container->bind(OrderItemRepository::class, OrderItemRepository::class);
    $container->bind(OrderRepository::class, OrderRepository::class);
    $container->bind(ProductCategoryRepository::class, ProductCategoryRepository::class);
    $container->bind(ProductOptionValueRepository::class, ProductOptionValueRepository::class);
    $container->bind(ProductVariantRepository::class, ProductVariantRepository::class);
    $container->bind(UserRepository::class, UserRepository::class);
    $container->bind(AuditLogRepository::class, AuditLogRepository::class);
    $container->bind(CartRepository::class, CartRepository::class);
    $container->bind(CouponRepository::class, CouponRepository::class);
    $container->bind(ImageRepository::class, ImageRepository::class);
    $container->bind(OrderDiscountRepository::class, OrderDiscountRepository::class);
    $container->bind(OrderPaymentRepository::class, OrderPaymentRepository::class);
    $container->bind(PaymentRepository::class, PaymentRepository::class);
    $container->bind(ProductOptionRepository::class, ProductOptionRepository::class);
    $container->bind(ProductRepository::class, ProductRepository::class);
    $container->bind(ProductVariantValueRepository::class, ProductVariantValueRepository::class);
  }
}