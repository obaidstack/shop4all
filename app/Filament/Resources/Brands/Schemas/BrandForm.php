<?php

namespace App\Filament\Resources\Brands\Schemas;

use App\Models\Brand;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;


class BrandForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make([
                    Grid::make(2)
                        ->schema([
                            TextInput::make('name')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn (string $operation, Set $set, $state) =>
                                    $operation === 'create' || $operation === 'edit' ? $set('slug', Str::slug($state)) : null),

                            TextInput::make('slug')
                                ->required()
                                ->disabled()
                                ->dehydrated()
                                ->unique(Brand::class, 'slug', ignoreRecord: true)
                                ->maxLength(255),
                        ]),
                    FileUpload::make('image')
                        ->image()
                        ->disk('public')
                        ->visibility('public')
                        ->directory('brands'),

                    Toggle::make('is_active')
                        ->required()
                        ->default(true),
                ])
                    ->columnSpanFull(),
            ]);
    }
}
