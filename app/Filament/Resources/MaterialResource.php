<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MaterialResource\Pages;
use App\Filament\Resources\MaterialResource\RelationManagers;
use App\Models\Material;
use App\Models\Course;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MaterialResource extends Resource
{
    protected static ?string $model = Material::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Materi')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->label('Judul Materi'),
                        Forms\Components\Textarea::make('description')
                            ->nullable()
                            ->columnSpanFull()
                            ->label('Deskripsi'),
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
                        Forms\Components\Select::make('file_type')
                            ->options([
                                'pdf' => 'PDF Document',
                                'video' => 'Video',
                                'ppt' => 'PowerPoint',
                                'doc' => 'Word Document',
                                'link' => 'External Link',
                            ])
                            ->default('pdf')
                            ->required()
                            ->label('Tipe File'),
                        Forms\Components\TextInput::make('order')
                            ->numeric()
                            ->default(0)
                            ->label('Urutan'),
                    ])->columns(2),

                Forms\Components\Section::make('File Upload')
                    ->schema([
                        Forms\Components\FileUpload::make('file_path')
                            ->label('File Materi')
                            ->directory('materials')
                            ->preserveFilenames()
                            ->maxSize(51200) // 50MB
                            ->visibility('public')
                            ->required()
                            ->acceptedFileTypes([
                                'application/pdf',
                                'video/*',
                                'application/vnd.ms-powerpoint',
                                'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                                'application/msword',
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->label('Judul'),
                Tables\Columns\TextColumn::make('course.title')
                    ->label('Kursus')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('file_type')
                    ->colors([
                        'danger' => 'pdf',
                        'success' => 'video',
                        'warning' => 'ppt',
                        'info' => 'doc',
                        'gray' => 'link',
                    ])
                    ->formatStateUsing(fn ($state) => match($state) {
                        'pdf' => 'PDF',
                        'video' => 'Video',
                        'ppt' => 'PPT',
                        'doc' => 'DOC',
                        'link' => 'Link',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('order')
                    ->sortable()
                    ->label('Urutan'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('course')
                    ->relationship('course', 'title'),
                Tables\Filters\SelectFilter::make('file_type')
                    ->options([
                        'pdf' => 'PDF',
                        'video' => 'Video',
                        'ppt' => 'PPT',
                        'doc' => 'DOC',
                        'link' => 'Link',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn (Material $record) => asset('storage/' . $record->file_path))
                    ->openUrlInNewTab(),
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
            'index' => Pages\ListMaterials::route('/'),
            'create' => Pages\CreateMaterial::route('/create'),
            'edit' => Pages\EditMaterial::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return 'Materials';
    }

    public static function getPluralLabel(): ?string
    {
        return 'Materials';
    }
}