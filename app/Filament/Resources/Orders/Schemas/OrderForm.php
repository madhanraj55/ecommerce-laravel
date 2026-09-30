<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Order Overview')
                    ->components([
                        TextInput::make('order_number')
                            ->label('Order Number')
                            ->readOnly()
                            ->required(),
                        TextInput::make('total')
                            ->label('Total Amount')
                            ->numeric()
                            ->prefix('₹')
                            ->readOnly()
                            ->required(),
                        Select::make('status')
                            ->label('Order Status')
                            ->required()
                            ->options([
                                'pending' => 'Pending',
                                'confirmed' => 'Confirmed',
                                'processing' => 'Processing',
                                'shipped' => 'Shipped',
                                'delivered' => 'Delivered',
                                'cancelled' => 'Cancelled',
                            ])
                            ->native(false),
                    ])
                    ->columns(3),

                Section::make('Customer & Shipping Details')
                    ->components([
                        TextInput::make('name')
                            ->label('Customer Name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        TextInput::make('phone')
                            ->label('Phone Number')
                            ->required()
                            ->maxLength(20),
                        Textarea::make('address')
                            ->label('Shipping Address')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(3),
            ]);
    }
}
