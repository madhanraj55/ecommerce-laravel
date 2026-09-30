<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Category Details')
                    ->description('Manage category name, slug, image, and active status.')
                    ->components([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state ?? ''))),
                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        FileUpload::make('image')
                            ->image()
                            ->disk('public')
                            ->directory('categories')
                            ->imageEditor()
                            ->columnSpanFull(),
                        Toggle::make('status')
                            ->label('Active Status')
                            ->helperText('Enable or disable category visibility in the store.')
                            ->default(true)
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }
}


