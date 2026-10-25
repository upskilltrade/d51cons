{{--
    Typographic lockup following the proposed identity (brick square, D51, CONSTRUCTION).
    Interim until the approved vector logo is supplied; swap the markup here only.
--}}
@props(['tone' => 'dark'])
<span {{ $attributes->class(['logo', 'logo--light' => $tone === 'light']) }}>
    <span class="logo__mark" aria-hidden="true"><span class="logo__square"></span>D51</span>
    <span class="logo__word" aria-hidden="true">Construction</span>
    <span class="visually-hidden">D51 Construction</span>
</span>
