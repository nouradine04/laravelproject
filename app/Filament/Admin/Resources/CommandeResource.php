<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CommandeResource\Pages;
use App\Filament\Admin\Resources\CommandeResource\RelationManagers;
use App\Models\Commande;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Mail;
use App\Mail\CommandePrete;
use Barryvdh\DomPDF\Facade\Pdf;


class CommandeResource extends Resource
{
    protected static ?string $model = Commande::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationLabel = 'Commandes';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Client')
                    ->relationship('user', 'name')
                    ->disabled()
                    ->required(),
                Forms\Components\Select::make('statut')
                    ->label('Statut')
                    ->options([
                        'en_attente' => 'En attente',
                        'en_preparation' => 'En préparation',
                        'prete' => 'Prête',
                        'payee' => 'Payée',
                    ])
                    ->reactive()
                    ->afterStateUpdated(function ($state, $record) {
                        if ($state === 'prete' && $record->statut !== 'prete') {
                            $pdf = Pdf::loadView('pdf.facture', ['commande' => $record]);
                            Mail::to($record->user->email)->send(new CommandePrete($record, $pdf->output()));
                        }
                    })
                    ->required(),
                Forms\Components\TextInput::make('montant_total')
                    ->label('Montant Total (€)')
                    ->numeric()
                    ->disabled(),
                Forms\Components\Section::make('Détails de la commande')
                    ->schema([
                        Forms\Components\Placeholder::make('details')
                            ->label('')
                            ->content(function ($record) {
                                if (!$record || !$record->details->count()) {
                                    return 'Aucun détail.';
                                }
                                $details = $record->details->map(function ($detail) {
                                    return "- {$detail->burger->nom} : {$detail->quantite} x {$detail->prix_unitaire} €";
                                })->join("\n");
                                return $details;
                            }),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('Client')->searchable(),
                Tables\Columns\TextColumn::make('statut')->label('Statut')->sortable(),
                Tables\Columns\TextColumn::make('montant_total')->label('Montant')->money('eur'),
                Tables\Columns\TextColumn::make('created_at')->label('Date')->dateTime()->sortable(),
            ])
        
            ->filters([
                Tables\Filters\SelectFilter::make('statut')->options([
                    'en_attente' => 'En attente',
                    'en_preparation' => 'En préparation',
                    'prete' => 'Prête',
                    'payee' => 'Payée',
                ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()->label('Annuler'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Annuler en masse'),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return []; // Plus de RelationManager
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCommandes::route('/'),
            'edit' => Pages\EditCommande::route('/{record}/edit'),
        ];
    }
}