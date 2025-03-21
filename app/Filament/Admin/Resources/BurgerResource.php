<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\BurgerResource\Pages;
use App\Models\Burger;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BurgerResource extends Resource
{
    protected static ?string $model = Burger::class;
    protected static ?string $navigationIcon = 'heroicon-o-fire';
    protected static ?string $navigationLabel = 'Burgers';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nom')
                    ->label('Nom')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('prix')
                    ->label('Prix (cffa)')
                    ->required()
                    ->numeric()
                    ->minValue(0),
                Forms\Components\FileUpload::make('image')
                    ->label('Image')
                    ->directory('burgers')
                    ->disk('public')
                    ->image(),
                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('stock')
                    ->label('Stock')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->minValue(0),
                Forms\Components\Toggle::make('archive')
                    ->label('Archive')
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nom')->label('Nom')->searchable(),
                Tables\Columns\TextColumn::make('prix')->label('Prix')->money('XAF')->sortable(), // Changé 'cffa' en 'XAF' (code devise CFA)
                Tables\Columns\ImageColumn::make('image')->label('Image'),
                Tables\Columns\TextColumn::make('stock')->label('Stock')->sortable(),
                Tables\Columns\IconColumn::make('archive')->label('Archivé')->boolean(),
                Tables\Columns\TextColumn::make('created_at')->label('Créé le')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')->label('Mis à jour')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('archive')->label('Non archivés')->query(fn ($query) => $query->where('archive', false)),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBurgers::route('/'),
            'create' => Pages\CreateBurger::route('/create'),
            'edit' => Pages\EditBurger::route('/{record}/edit'),
        ];
    }
}