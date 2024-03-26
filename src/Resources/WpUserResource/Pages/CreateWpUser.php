<?php

namespace Moox\Press\Resources\WpUserResource\Pages;


use App\Models\User;
use Moox\Press\Models\WpUser;
use Moox\Press\Models\WpUserMeta;
use Filament\Notifications\Notification;
use Moox\Press\Resources\WpUserResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;

class CreateWpUser extends CreateRecord
{
    protected static string $resource = WpUserResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
{
    if (filled($data['first_name'])) {

        config(['press.user_meta.first_name' => $data['first_name']]);
    }

    return $data;
}

    public function afterCreate(): void
    {

        $metaDataConfig = config('press.user_meta');


        foreach ($metaDataConfig as $metaKey => $metaValue) {
            if($metaKey === 'nickname' || $metaKey === 'first_name') {
                $metaValue = $this->record->$metaValue;

            }

            $userId = $this->record->ID;

                WpUserMeta::create([
                    'user_id' => $userId,
                    'meta_key' => $metaKey,
                    'meta_value' => $metaValue,
                ]);

        }


    }
}
