<x-dynamic-component :component="$getFieldWrapperView()" :field="$turnstile">
    <div
        wire:ignore
        x-load-js="[
                'https://challenges.cloudflare.com/turnstile/v0/api.js?render=explicit&onload=onTurnstileLoad',
            ]"
        x-data="{
            state: $wire.entangle('{{ $getStatePath() }}').defer,
            widgetId: null,
        }"
        x-init="
            widgetId = null;

            (() => {
                let options = {
                    sitekey: '{{ config('filament-turnstile.key') }}',
                    theme: '{{ $getTheme() }}',
                    size: '{{ $getSize() }}',
                    language: '{{ $getLanguage() }}',
                    callback: function (token) {
                        $wire.set('{{ $getStatePath() }}', token)
                    },
                    'error-callback': function () {
                        $wire.set('{{ $getStatePath() }}', null)
                    },
                }

                // Render widget when Turnstile API is ready
                const renderWidget = () => {
                    if (! window.turnstile || ! $refs.turnstile || widgetId !== null) {
                        return
                    }

                    widgetId = turnstile.render($refs.turnstile, options)
                }

                // Called when Turnstile API loads
                window.onTurnstileLoad = () => {
                    renderWidget()
                }

                // If API already loaded (on re-render), render immediately
                if (window.turnstile) {
                    renderWidget()
                }

                $wire.on('{{ $getResetEvent() }}', () => {
                    if (widgetId !== null && window.turnstile) {
                        turnstile.reset(widgetId)
                    }
                })

                // Cleanup when component is destroyed
                return () => {
                    if (widgetId !== null && window.turnstile) {
                        turnstile.remove(widgetId)
                        widgetId = null
                    }
                }
            })()
        "
        @class([
            'flex',
            $getAlignmentClasses(),
        ])
    >
        <div x-ref="turnstile"></div>
    </div>
</x-dynamic-component>
