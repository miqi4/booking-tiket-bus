<?php

namespace App\Filament\Resources\Schedules\Tables;

use App\Models\Schedule;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SchedulesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('busRoute.id')
                    ->searchable(),
                TextColumn::make('bus.name')
                    ->searchable(),
                TextColumn::make('departure_at')
                    ->dateTime('M j, Y H:i')
                    ->sortable(),
                TextColumn::make('arrival_est')
                    ->dateTime('M j, Y H:i')
                    ->sortable(),
                TextColumn::make('price')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('available_seats')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('replicate_to_date')
                    ->label('Duplikat ke Tanggal Lain')
                    ->icon('heroicon-o-document-duplicate')
                    ->color('info')
                    ->modalHeading('Duplikat Jadwal')
                    ->form([
                        DateTimePicker::make('departure_at')
                            ->label('Tanggal Berangkat Baru')
                            ->default(fn (Schedule $record) => $record->departure_at)
                            ->required(),
                        DateTimePicker::make('arrival_est')
                            ->label('Estimasi Tiba Baru')
                            ->default(fn (Schedule $record) => $record->arrival_est),
                    ])
                    ->action(function (Schedule $record, array $data): void {
                        $newSchedule = $record->replicate();
                        $newSchedule->departure_at = $data['departure_at'];
                        $newSchedule->arrival_est = $data['arrival_est'];
                        $newSchedule->status = 'active';
                        // Reset available seats to bus capacity if available
                        $newSchedule->available_seats = $record->bus?->capacity ?? $record->available_seats;
                        $newSchedule->save();
                    })
                    ->successNotificationTitle('Jadwal berhasil diduplikasi ke tanggal baru'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
