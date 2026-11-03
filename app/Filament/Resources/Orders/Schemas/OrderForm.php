<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Product;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ToggleButtons;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Number;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()->
                schema([
                    Section::make('Order Information')
                    ->schema([
                        Select::make('user_id')
                            ->label('Customer')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('payment_method')
                            ->options([
                                'stripe' => 'Stripe',
                                'cod' => 'Cash on delivery',
                            ])
                            ->required(),
                        Select::make('payment_status')
                            ->options([
                                'pending' => 'Pending',
                                'paid' => 'Paid',
                                'failed' => 'Failed',
                            ])
                            ->default('pending')
                            ->required(),
                        ToggleButtons::make('status')
                            ->inline()
                            ->default('new')
                            ->required()
                            ->options([
                                'new' => 'New',
                                'processing' => 'Processing',
                                'shipped' => 'Shipped',
                                'delivered' => 'Delivered',
                                'cancelled' => 'Cancelled',
                            ])
                            ->colors([
                                'new' => 'info',
                                'processing' => 'warning',
                                'shipped' => 'success',
                                'delivered' => 'gray',
                                'cancelled' => 'danger',
                            ])
                            ->icons([
                                'new' => 'heroicon-m-sparkles',
                                'processing' => 'heroicon-m-arrow-path',
                                'shipped' => 'heroicon-m-truck',
                                'delivered' => 'heroicon-m-check-badge',
                                'cancelled' => 'heroicon-m-x-circle',
                            ]),
                            Select::make('currency')
                            ->options([
                                'pkr' => 'PKR',
                                'usd' => 'USD',
                                'eur' => 'EUR',
                                'gbp' => 'GBP',
                            ])
                            ->default('pkr')
                            ->required(),
                            Select::make('shipping_method')
                            ->options([
                                'tcs' => 'TCS',
                                'leopards' => 'Leopards',
                                'postex' => 'PostEx',
                                'blue-ex' => 'Blue-ex',
                            ]),
                            Textarea::make('notes')
                                ->columnSpanFull(),
                    ])->columns(2),
                    
                    Section::make('Order Items')
                    ->schema([
                        Repeater::make('items')
                            ->relationship()
                        ->schema([
                            Select::make('product_id')
                                ->relationship('product', 'name')
                                ->searchable()
                                ->preload()
                                ->required()
                                ->distinct()
                                ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                ->reactive()
                                ->afterStateUpdated(function ($state, Set $set, Get $get): void {
                                    $unitAmount = Product::find($state)?->price ?? 0;
                                    $set('unit_amount', $unitAmount);
                                    $set('total_amount', $unitAmount * ($get('quantity') ?? 1));
                                })
                                // ->afterStateUpdated(
                                //     fn($state, Set $set) =>
                                //         $set('total_amount', Product::find($state)?->price ?? 0 ))
                                // ->afterStateUpdated(
                                //     fn($state, Set $set) =>
                                //         $set('unit_amount', Product::find($state)?->price ?? 0 ))
                                // ->afterStateUpdated(
                                //     fn($state, Set $set) =>
                                //         $set('total_amount', Product::find($state)?->price ?? 0 ))
                                ->columnSpan(4),
                            
                            TextInput::make('quantity')
                                ->required()
                                ->numeric()
                                ->default(1)
                                ->minValue(1)
                                ->reactive()
                                ->afterStateUpdated(
                                    fn($state, Set $set, Get $get) =>
                                        $set('total_amount', $state * $get('unit_amount') ))
                                ->columnSpan(2),

                            TextInput::make('unit_amount')
                                ->required()
                                ->numeric()
                                ->disabled()
                                ->dehydrated()
                                ->columnSpan(3),

                            TextInput::make('total_amount')
                                ->required()
                                ->numeric()
                                ->disabled()
                                ->dehydrated()
                                ->columnSpan(3),

                        ])->columns(12),

                        TextEntry::make('total_field')
                            ->label('Grand Total')
                            ->color('success')
                            ->state( function (Get $get, Set $set) {
                                $total = 0;
                                if(!$repeaters = $get('items')) {
                                    return $total;
                                }
                                foreach($repeaters as $key => $rep) {
                                    $total += $get("items.{$key}.total_amount");
                                }
                                $set('grand_total', $total);
                                return Number::currency($total, $get('currency'));
                        }),
                        Hidden::make('grand_total')
                            ->default(0),
                    ]),

                ])->columnSpanFull(),
            ]);
    }
}
