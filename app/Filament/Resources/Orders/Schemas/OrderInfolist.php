<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Order Information')
                    ->components([
                        TextEntry::make('order_number')
                            ->label('Order Number')
                            ->badge()
                            ->copyable(),
                        TextEntry::make('name')
                            ->label('Customer Name'),
                        TextEntry::make('email')
                            ->label('Email Address')
                            ->icon('heroicon-m-envelope'),
                        TextEntry::make('phone')
                            ->label('Phone Number')
                            ->icon('heroicon-m-phone'),
                        TextEntry::make('address')
                            ->label('Delivery Address')
                            ->columnSpanFull(),
                        TextEntry::make('status')
                            ->label('Order Status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'pending' => 'warning',
                                'confirmed', 'processing' => 'info',
                                'shipped' => 'primary',
                                'delivered' => 'success',
                                'cancelled' => 'danger',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                        TextEntry::make('total')
                            ->label('Order Total')
                            ->money('INR')
                            ->weight('bold'),
                        TextEntry::make('created_at')
                            ->label('Order Date')
                            ->dateTime('M d, Y h:i A'),
                    ])
                    ->columns(3),

                Section::make('Order Items')
                    ->components([
                        RepeatableEntry::make('items')
                            ->label('')
                            ->schema([
                                TextEntry::make('product.name')
                                    ->label('Product Name')
                                    ->weight('bold'),
                                TextEntry::make('quantity')
                                    ->label('Quantity'),
                                TextEntry::make('price')
                                    ->label('Unit Price')
                                    ->money('INR'),
                                TextEntry::make('subtotal')
                                    ->label('Subtotal')
                                    ->money('INR')
                                    ->weight('bold'),
                            ])
                            ->columns(4)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
