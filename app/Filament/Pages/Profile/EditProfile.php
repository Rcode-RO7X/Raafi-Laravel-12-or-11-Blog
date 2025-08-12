<?php

namespace App\Filament\Pages\Profile;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class EditProfile extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static string $view = 'filament.pages.profile.edit-profile';

    protected static ?string $slug = 'profile';

    protected static ?string $navigationLabel = 'Profile';

    protected static ?int $navigationSort = 10;

    protected static ?string $title = 'Edit Profile';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'name' => Auth::user()->name,
            'email' => Auth::user()->email,
            'avatar_url' => Auth::user()->avatar_url ?? null,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Username')
                    ->rules(['string', 'max:255']),
                TextInput::make('email')
                    ->email()
                    ->maxLength(255)
                    ->disabled(),
                FileUpload::make('avatar_url')
                    ->label('Profile Picture')
                    ->image()
                    ->directory('avatars')
                    ->maxSize(10240) // 10MB
                    ->visibility('public')
                    ->avatar()
                    ->rules(['nullable', 'image', 'max:10240']),
            ]);
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();
            
            $user = Auth::user();
            
            // Check if we have a valid user
            if (!$user) {
                Notification::make()
                    ->danger()
                    ->title('Error updating profile')
                    ->body('User not found.')
                    ->send();
                return;
            }
            
            // Fetch fresh user instance from database
            $user = User::find($user->id);
            
            // Check if we have a valid user instance
            if (!$user) {
                Notification::make()
                    ->danger()
                    ->title('Error updating profile')
                    ->body('User not found in database.')
                    ->send();
                return;
            }
            
            // Prepare data for update
            $updateData = [
                'name' => $data['name'],
            ];
            
            // Only update avatar_url if it was changed
            if (isset($data['avatar_url'])) {
                $updateData['avatar_url'] = $data['avatar_url'];
            }
            
            // Update the user
            $user->update($updateData);

            Notification::make()
                ->success()
                ->title('Profile updated successfully!')
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title('Error updating profile')
                ->body('An error occurred while updating your profile. Please try again.')
                ->send();
        }
    }
}