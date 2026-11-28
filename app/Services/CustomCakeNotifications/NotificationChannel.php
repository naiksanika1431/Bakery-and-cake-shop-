<?php

namespace App\Services\CustomCakeNotifications;

use App\Models\Order;

interface NotificationChannel
{
    public function name(): string;

    public function provider(): string;

    public function recipient(Order $order): string;

    public function send(Order $order, string $message): array;
}