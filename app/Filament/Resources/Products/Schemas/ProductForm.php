<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Product Details')
                    ->description('General information about the product.')
                    ->components([
                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state ?? ''))),
                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Textarea::make('description')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Pricing & Inventory')
                    ->components([
                        TextInput::make('price')
                            ->required()
                            ->numeric()
                            ->prefix('₹')
                            ->minValue(0),
                        TextInput::make('sale_price')
                            ->nullable()
                            ->numeric()
                            ->prefix('₹')
                            ->minValue(0),
                        TextInput::make('stock')
                            ->required()
                            ->integer()
                            ->minValue(0)
                            ->default(0),
                        Toggle::make('status')
                            ->label('Active Status')
                            ->helperText('Enable or disable visibility in the store.')
                            ->default(true)
                            ->required(),
                    ])
                    ->columns(2),

                Section::make('Product Media')
                    ->components([
                        FileUpload::make('image')
                            ->image()
                            ->disk('public')
                            ->directory('products')
                            ->imageEditor()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}


