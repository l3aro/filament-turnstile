<?php

namespace l3aro\FilamentTurnstile\Forms;

use Closure;
use Filament\Forms\Components\Field;
use Filament\Support\Concerns\HasAlignment;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\ArgumentValue;
use l3aro\FilamentTurnstile\Enums\TurnstileSize;
use l3aro\FilamentTurnstile\Enums\TurnstileTheme;
use l3aro\FilamentTurnstile\Facades\FilamentTurnstileFacade;
use l3aro\FilamentTurnstile\Rules\TurnstileRule;

class Turnstile extends Field
{
    use HasAlignment;

    protected string $view = 'filament-turnstile::forms.turnstile';

    protected string $viewIdentifier = 'turnstile';

    protected string|Closure|ArgumentValue|TurnstileTheme|null $theme = ArgumentValue::Default;

    protected string|Closure|ArgumentValue|TurnstileSize|null $size = ArgumentValue::Default;

    protected string|Closure|ArgumentValue|null $language = ArgumentValue::Default;

    protected string|Closure|ArgumentValue|null $resetEvent = ArgumentValue::Default;

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->required()
            ->hiddenLabel()
            ->rule(new TurnstileRule())
            ->dehydrated(false);
    }

    public function theme(string|Closure|ArgumentValue|TurnstileTheme|null $theme): static
    {
        $this->theme = $this->evaluate($theme);

        return $this;
    }

    public function size(string|Closure|ArgumentValue|TurnstileSize|null $size): static
    {
        $this->size = $this->evaluate($size);

        return $this;
    }

    public function language(string|Closure|ArgumentValue|null $language): static
    {
        $this->language = $this->evaluate($language);

        return $this;
    }

    public function resetEvent(string|Closure|ArgumentValue|null $resetEvent): static
    {
        $this->resetEvent = $this->evaluate($resetEvent);

        return $this;
    }

    public function getTheme(): ?string
    {
        $theme = $this->evaluate($this->theme);

        return $theme === ArgumentValue::Default ? 'auto' : $theme;
    }

    public function getSize(): ?string
    {
        $size = $this->evaluate($this->size);

        return $size === ArgumentValue::Default ? 'normal' : $size;
    }

    public function getLanguage(): ?string
    {
        $language = $this->evaluate($this->language);

        return $language === ArgumentValue::Default ? app()->getLocale() : $language;
    }

    public function getResetEvent(): ?string
    {
        $resetEvent = $this->evaluate($this->resetEvent);

        return $resetEvent === ArgumentValue::Default ? FilamentTurnstileFacade::getResetEventName() : $resetEvent;
    }

    public function getAlignmentClasses(): ?string
    {
        return match ($this->getAlignment()) {
            Alignment::Center => 'justify-center',
            Alignment::Left => 'justify-start',
            Alignment::Right => 'justify-end',
            Alignment::Between => 'justify-between',
            Alignment::Start => 'justify-start',
            Alignment::End => 'justify-end',
            default => null,
        };
    }
}
