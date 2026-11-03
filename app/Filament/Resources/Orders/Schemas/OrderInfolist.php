<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;
use Illuminate\Support\Str;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        $currency = fn ($livewire): string => strtoupper($livewire->getRecord()->currency ?? 'PKR');

        return $schema
            ->components([
                Section::make('Order summary')
                    ->schema([
                        TextEntry::make('id')
                            ->label('Order number')
                            ->prefix('#')
                            ->copyable(),
                        TextEntry::make('user.name')
                            ->label('Customer')
                            ->icon('heroicon-m-user'),
                        TextEntry::make('user.email')
                            ->label('Email')
                            ->icon('heroicon-m-envelope')
                            ->copyable(),
                        TextEntry::make('status')
                            ->badge()
                            ->formatStateUsing(fn (?string $state): string => Str::headline($state ?? 'Unknown'))
                            ->color(fn (?string $state): string => match ($state) {
                                'new' => 'info',
                                'processing' => 'warning',
                                'shipped' => 'primary',
                                'delivered' => 'success',
                                'cancelled' => 'danger',
                                default => 'gray',
                            }),
                        TextEntry::make('grand_total')
                            ->label('Order total')
                            ->money($currency)
                            ->size(TextSize::Large)
                            ->weight(FontWeight::Bold)
                            ->color('success'),
                    ])
                    ->columns(5)
                    ->columnSpanFull(),

                Section::make('Payment & delivery')
                    ->schema([
                        TextEntry::make('payment_method')
                            ->label('Payment method')
                            ->formatStateUsing(fn (?string $state): string => match ($state) {
                                'cod' => 'Cash on delivery',
                                'stripe' => 'Stripe',
                                default => Str::headline($state ?? 'Not set'),
                            }),
                        TextEntry::make('payment_status')
                            ->label('Payment status')
                            ->badge()
                            ->formatStateUsing(fn (?string $state): string => Str::headline($state ?? 'Not set'))
                            ->color(fn (?string $state): string => match ($state) {
                                'paid' => 'success',
                                'pending' => 'warning',
                                'failed' => 'danger',
                                default => 'gray',
                            }),
                        TextEntry::make('shipping_method')
                            ->label('Shipping method')
                            ->formatStateUsing(fn (?string $state): string => match ($state) {
                                'tcs' => 'TCS',
                                'leopards' => 'Leopards',
                                'postex' => 'PostEx',
                                'blue-ex' => 'Blue-Ex',
                                default => Str::headline($state ?? 'Not set'),
                            }),
                        TextEntry::make('shipping_amount')
                            ->label('Shipping cost')
                            ->money($currency)
                            ->placeholder('Not set'),
                    ])
                    ->columns(2),

                Section::make('Shipping address')
                    ->schema([
                        TextEntry::make('address.full_name')
                            ->label('Recipient')
                            ->placeholder('No address on file'),
                        TextEntry::make('address.phone')
                            ->label('Phone')
                            ->placeholder('Not provided'),
                        TextEntry::make('address.street_address')
                            ->label('Street address')
                            ->placeholder('Not provided')
                            ->columnSpanFull(),
                        TextEntry::make('address.city')
                            ->label('City')
                            ->placeholder('Not provided'),
                        TextEntry::make('address.state')
                            ->label('State / region')
                            ->placeholder('Not provided'),
                        TextEntry::make('address.zip_code')
                            ->label('Postal code')
                            ->placeholder('Not provided'),
                    ])
                    ->columns(2),

                Section::make('Order items')
                    ->schema([
                        RepeatableEntry::make('items')
                            ->hiddenLabel()
                            ->placeholder('No items in this order.')
                            ->table([
                                TableColumn::make('Product'),
                                TableColumn::make('Quantity'),
                                TableColumn::make('Unit price'),
                                TableColumn::make('Total'),
                            ])
                            ->schema([
                                TextEntry::make('product.name')
                                    ->weight(FontWeight::Medium),
                                TextEntry::make('quantity')
                                    ->numeric(),
                                TextEntry::make('unit_amount')
                                    ->money($currency),
                                TextEntry::make('total_amount')
                                    ->money($currency)
                                    ->weight(FontWeight::SemiBold),
                            ]),
                    ])
                    ->columnSpanFull(),

                Section::make('Notes')
                    ->schema([
                        TextEntry::make('notes')
                            ->hiddenLabel()
                            ->placeholder('No notes for this order.')
                            ->prose(),
                    ]),

                Section::make('Record history')
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Created')
                            ->dateTime()
                            ->placeholder('Not available'),
                        TextEntry::make('updated_at')
                            ->label('Last updated')
                            ->dateTime()
                            ->placeholder('Not available'),
                    ])
                    ->columns(2),
            ])
            ->columns([
                'default' => 1,
                'lg' => 2,
            ]);
    }
}
