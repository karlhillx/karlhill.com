<?php

it('redirects Dry Standard client paths to drinkdrystandard.com', function () {
    $this->get('/clients/the-dry-standard/')
        ->assertRedirect('https://drinkdrystandard.com/');

    $this->get('/clients/the-dry-standard/reviews/beer/guinness-0-0/')
        ->assertRedirect('https://drinkdrystandard.com/reviews/beer/guinness-0-0/');

    $this->get('/clients/the-dry-standard/compare/?slugs=a,b')
        ->assertRedirect();

    expect($this->get('/clients/the-dry-standard/compare/?slugs=a,b')->headers->get('Location'))
        ->toStartWith('https://drinkdrystandard.com/compare/')
        ->toContain('slugs=');
});
