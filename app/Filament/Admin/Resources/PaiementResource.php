<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PaiementResource\Pages;
use App\Filament\Admin\Resources\PaiementResource\RelationManagers;
use App\Models\Paiement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Mail\CommandePayee;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;



class PaiementResource extends Resource
{
    protected static ?string $model = Paiement::class;
    protected static ?string $navigationIcon = 'heroicon-o-currency-euro';
    protected static ?string $navigationLabel = 'Paiements';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('commande_id')
                    ->label('Commande')
                    ->options(function () {
                        return \App\Models\Commande::where('statut', 'en_attente')
                            ->whereDoesntHave('paiement')
                            ->pluck('id', 'id')
                            ->map(fn ($id) => "Commande #$id");
                    })
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $commande = \App\Models\Commande::find($state);
                        if ($commande) {
                            if ($commande->paiement()->exists()) {
                                $set('commande_id', null);
                                Notification::make()->title('Erreur')->body('Commande déjà payée')->danger()->send();
                            } else {
                                $set('montant', $commande->montant_total);
                            }
                        }
                    }),
                Forms\Components\TextInput::make('montant')
                    ->label('Montant (cffa)')
                    ->numeric()
                    ->required()
                    ->disabled()
                    ->dehydrated(true),
                Forms\Components\DateTimePicker::make('date_paiement')
                    ->label('Date de paiement')
                    ->default(now())
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('commande.user.name')->label('Commande'),
                Tables\Columns\TextColumn::make('montant')->label('Montant')->money('eur'),
                Tables\Columns\TextColumn::make('date_paiement')->label('Date')->dateTime(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPaiements::route('/'),
            'create' => Pages\CreatePaiement::route('/create'),
            'edit' => Pages\EditPaiement::route('/{record}/edit'),
        ];
    }

    public static function afterCreate($record)
    {
        $commande = $record->commande;
        if ($commande) {
            $commande->update(['statut' => 'payee']);
            $pdf = Pdf::loadView('pdf.facture', ['commande' => $commande]);
            Mail::to($commande->user->email)->send(new CommandePayee($commande, $pdf->output()));
        }
    }
}
