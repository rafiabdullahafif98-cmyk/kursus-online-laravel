<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EnrollmentResource\Pages;
use App\Filament\Resources\EnrollmentResource\RelationManagers;
use App\Models\Enrollment;
use App\Models\User;
use App\Models\Course;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EnrollmentResource extends Resource
{
    protected static ?string $model = Enrollment::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Enrollment')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Siswa')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required(),
                                Forms\Components\TextInput::make('email')
                                    ->email()
                                    ->required(),
                                Forms\Components\TextInput::make('password')
                                    ->password()
                                    ->required(),
                                Forms\Components\Hidden::make('role')
                                    ->default('siswa'),
                            ]),
                        Forms\Components\Select::make('course_id')
                            ->label('Kursus')
                            ->relationship('course', 'title')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('title')
                                    ->required(),
                            ]),
                        Forms\Components\Select::make('status')
                            ->options([
                                'active' => 'Aktif',
                                'completed' => 'Selesai',
                                'cancelled' => 'Dibatalkan',
                            ])
                            ->default('active')
                            ->required(),
                        Forms\Components\DateTimePicker::make('enrolled_at')
                            ->default(now())
                            ->required(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Siswa')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('course.title')
                    ->label('Kursus')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'active',
                        'warning' => 'completed',
                        'danger' => 'cancelled',
                    ])
                    ->formatStateUsing(fn ($state) => match($state) {
                        'active' => 'Aktif',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('enrolled_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user')
                    ->relationship('user', 'name'),
                Tables\Filters\SelectFilter::make('course')
                    ->relationship('course', 'title'),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Aktif',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    ]),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEnrollments::route('/'),
            'create' => Pages\CreateEnrollment::route('/create'),
            'edit' => Pages\EditEnrollment::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return 'Enrollments';
    }

    public static function getPluralLabel(): ?string
    {
        return 'Enrollments';
    }
}