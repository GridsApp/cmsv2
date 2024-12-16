
<div>
    <label class="twa-form-label">
        {{$info['label']}}
</label>
<div class="twa-form-input-container">
<div >
<div class="twa-form-input-ring">
    <textarea @if($info['index'] ?? null) tabindex="{{$info['index']}}" @endif wire:model="value" x-ref="textarea" rows="3" class="twa-form-input resize-none "></textarea>
</div>
    </div>
</div>
    @error(!(isset($info['translatable']) && $info['translatable']) && get_field_modal($info) ?? 'value')
    <span class="form-error-message">


        {{$message}}
        </span>
    @enderror
</div>
