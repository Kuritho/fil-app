<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Filament\Resources\PaymentResource\RelationManagers;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('payment_method')
                ->required()
                ->label('Payment Method'),
    
            TextInput::make('amount')
                ->numeric()
                ->required()
                ->label('Amount'),
    
            Select::make('currency')
                ->options([
                    'usd' => 'USD',
                    'peso' => 'Philippine Peso'
                ])
                ->label('Currency'),
    
            Select::make('status')
                ->options([
                    'success' => 'Success',
                    'pending' => 'Pending',
                    'failed' => 'Failed',
                ])
                ->label('Status'),
    
            TextInput::make('transaction_id')
                ->label('Transaction ID'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('payment_method')->label('Payment Method'),
            TextColumn::make('amount')->label('Amount'),
            TextColumn::make('currency')->label('Currency'),
            TextColumn::make('status')->label('Status'),
            TextColumn::make('transaction_id')->label('Transaction ID'),
            TextColumn::make('created_at')->dateTime()->label('Created At'),
        ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}
