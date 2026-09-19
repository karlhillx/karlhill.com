#!/usr/bin/env python3
"""Generate the master product CSV and merge new SKUs into the review queue."""

from __future__ import annotations

import csv
from pathlib import Path

ROOT = Path(__file__).resolve().parent

HEADERS = [
    "ID",
    "EAN",
    "Product",
    "Brand",
    "Category",
    "ABV",
    "Dealcoholized?",
    "Method",
    "Retailer(s)",
    "Times Purchased",
    "First Purchase",
    "Most Recent Purchase",
]

# Queue-only products that are not yet in the master table.
EXTRA_SKUS = {
    "Athletic Brewing Run Wild IPA": "TDS-0101",
    "Sober Spirits Whisky": "TDS-0102",
    "Heineken 0.0": "TDS-0103",
}

# Published queue labels that map to an existing master-table product.
SOURCED_EANS = {
    "Be Free Rose Non-Alcoholic Wine": "4003301079788",
    "Appalina Alcohol Free Chardonnay": "4049366003207",
    "Be Free White Sparkling Non-Alcoholic Wine": "4003301080005",
    'Greenbar "UNRum + Cola" Non-Alcoholic Canned Cocktail': "855675002565",
}

SKU_ALIASES = {
    "Giesen 0% Sauvignon Blanc": "Giesen Non-Alcoholic Sauvignon Blanc",
    "Freixenet 0.0": "Freixenet Non-Alcoholic Sparkling",
    "Spiritless Kentucky 74": 'Spiritless "Kentucky 74" Non-Alcoholic Whiskey Spirit',
    "St. Regis Non-Alcoholic Rosé": "St. Regis Non-Alcoholic Rose",
}

# One row per unique purchased product, already sorted by times purchased desc.
PURCHASED: list[dict[str, str]] = [
    {"product": "Be Free Rose Non-Alcoholic Wine", "brand": "Be Free", "category": "Non-alcoholic wine", "abv": "Not verified", "dealcoholized": "Yes", "method": 'Dealcoholized ("special method", not detailed)', "retailers": "Total Wine, Total Wine (Instacart)", "times": "31", "first": "2026-01-11", "recent": "2026-09-02"},
    {"product": "Magic Box Vanish Non-Alcoholic Riesling", "brand": "Magic Box", "category": "Non-alcoholic wine", "abv": "Not verified (industry-typical ≤0.5%)", "dealcoholized": "Yes", "method": "Dealcoholized (method not publicly detailed)", "retailers": "Total Wine, Total Wine (Instacart)", "times": "23", "first": "2026-01-11", "recent": "2026-09-17"},
    {"product": "Appalina Alcohol Free Chardonnay", "brand": "Appalina", "category": "Non-alcoholic wine", "abv": "Not verified", "dealcoholized": "Yes", "method": "Not verified", "retailers": "Total Wine", "times": "23", "first": "2026-03-28", "recent": "2026-08-14"},
    {"product": "Be Free White Sparkling Non-Alcoholic Wine", "brand": "Be Free", "category": "Non-alcoholic sparkling wine", "abv": "Not verified", "dealcoholized": "Yes", "method": 'Dealcoholized ("special method", not detailed)', "retailers": "Total Wine", "times": "13", "first": "2026-04-03", "recent": "2026-07-29"},
    {"product": 'Halfway Crooks Non-Alcoholic IPA "Brevet"', "brand": "Halfway Crooks Beer", "category": "Non-alcoholic beer", "abv": "Not verified", "dealcoholized": "No (arrested fermentation)", "method": "Same brewery NA process as Brevet Pils — Chiber extract keeps yeast static, arresting fermentation rather than removing alcohol (Atlanta Magazine; Craft Beer & Brewing describes the Brevet line as lager and IPA)", "retailers": "Metro Wine & Spirits", "times": "9", "first": "2026-03-21", "recent": "2026-08-22"},
    {"product": "Be Free Chardonnay Non-Alcoholic Wine", "brand": "Be Free", "category": "Non-alcoholic wine", "abv": "Not verified", "dealcoholized": "Yes", "method": 'Dealcoholized ("special method", not detailed)', "retailers": "Total Wine", "times": "9", "first": "2026-03-28", "recent": "2026-09-17"},
    {"product": "Giesen 0% Non-Alcoholic Riesling", "brand": "Giesen", "category": "Non-alcoholic wine", "abv": "≤0.5%", "dealcoholized": "Yes", "method": "Spinning cone technology (gentle distillation); full-strength wine made then dealcoholized", "retailers": "Wegmans (Instacart), Total Wine", "times": "8", "first": "2026-01-31", "recent": "2026-04-03"},
    {"product": 'Halfway Crooks Non-Alcoholic Pilsner "Brevet"', "brand": "Halfway Crooks Beer", "category": "Non-alcoholic beer", "abv": "<0.5% (brewery); 0.3% per BeerMenus", "dealcoholized": "No (arrested fermentation)", "method": "Chiber mushroom extract keeps yeast static, halting alcohol production during fermentation (Atlanta Magazine)", "retailers": "Metro Wine & Spirits", "times": "8", "first": "2026-02-22", "recent": "2026-08-22"},
    {"product": "DC Brau Non-Alcoholic Pale Ale", "brand": "DC Brau Brewing Co.", "category": "Non-alcoholic beer", "abv": "0.3% (producer, per DC Beer interview)", "dealcoholized": "Yes", "method": "Mechanical separator removing alcohol from finished beer, combined with a hybrid low-alcohol Lallemand yeast", "retailers": "Total Wine, Total Wine (Instacart), Metro Wine & Spirits", "times": "6", "first": "2026-01-11", "recent": "2026-08-28"},
    {"product": "Clearscape Non-Alcoholic Rose", "brand": "Clearscape", "category": "Non-alcoholic wine", "abv": "Not verified", "dealcoholized": "Yes", "method": "Not verified in detail", "retailers": "Total Wine (Instacart), Total Wine", "times": "6", "first": "2026-01-21", "recent": "2026-09-06"},
    {"product": "Freixenet Non-Alcoholic Sparkling Rose", "brand": "Freixenet", "category": "Non-alcoholic sparkling wine", "abv": "Not verified", "dealcoholized": "Yes", "method": 'Branded "Alcohol Removed"; specific method not detailed', "retailers": "Metro Wine & Spirits", "times": "6", "first": "2026-01-23", "recent": "2026-05-10"},
    {"product": "Almost Zero Ravishing Rose Non-Alcoholic Wine", "brand": "Almost Zero", "category": "Non-alcoholic wine", "abv": "Not verified", "dealcoholized": "Yes", "method": "Spinning cone column after full vinification (producer)", "retailers": "Total Wine (Instacart), Total Wine", "times": "5", "first": "2026-01-21", "recent": "2026-09-17"},
    {"product": "Savyll Non-Alcoholic Moscow Mule", "brand": "Savyll", "category": "Non-alcoholic cocktails/RTDs", "abv": "Not verified", "dealcoholized": "No (formulated)", "method": "Not verified", "retailers": "Total Wine", "times": "5", "first": "2026-04-03", "recent": "2026-07-02"},
    {"product": '90+ Cellars Alcohol-Removed Sparkling Rosé "Lot 229"', "brand": "90+ Cellars", "category": "Non-alcoholic sparkling wine", "abv": "<0.5%", "dealcoholized": "Yes", "method": "Traditionally made, then alcohol removed via reverse osmosis", "retailers": "Metro Wine & Spirits", "times": "4", "first": "2026-01-23", "recent": "2026-08-08"},
    {"product": "Lagunitas Non-Alcoholic Hazy IPNA", "brand": "Lagunitas", "category": "Non-alcoholic beer", "abv": "0.5%", "dealcoholized": "No (fermented, residual alcohol)", "method": "Brewed with same ingredients as regular Hazy IPA, fermentation halted early — not dealcoholized", "retailers": "Total Wine", "times": "4", "first": "2026-06-05", "recent": "2026-08-11"},
    {"product": 'Josef Leitz Non-Alcoholic Sparkling Riesling "Eins Zwei Zero"', "brand": "Weingut Leitz", "category": "Non-alcoholic sparkling wine", "abv": "0%", "dealcoholized": "Yes", "method": "Vacuum distillation at low temperature", "retailers": "Metro Wine & Spirits, Upside Drinks", "times": "3", "first": "2025-10-21", "recent": "2026-03-04"},
    {"product": "Dr. Heidemanns Bergweiler Non-Alcoholic Riesling", "brand": "Dr. Heidemanns Bergweiler", "category": "Non-alcoholic wine", "abv": "Not verified", "dealcoholized": "Not verified", "method": "Not verified", "retailers": "Total Wine (Instacart)", "times": "3", "first": "2026-01-21", "recent": "2026-02-14"},
    {"product": "Giesen 0% Non-Alcoholic Rose", "brand": "Giesen", "category": "Non-alcoholic wine", "abv": "≤0.5%", "dealcoholized": "Yes", "method": "Spinning cone technology", "retailers": "Wegmans (Instacart), Total Wine", "times": "3", "first": "2026-01-31", "recent": "2026-03-24"},
    {"product": "Band of Vintners Freestyle Non-Alcoholic Skin-Contact Wine", "brand": "Band of Vintners", "category": "Non-alcoholic wine", "abv": "Not verified", "dealcoholized": "Yes", "method": 'Brand states wine is "skin fermented with native yeasts" and balanced "once the alcohol is removed"; specific removal technique not disclosed', "retailers": "The Zero Proof", "times": "3", "first": "2026-03-06", "recent": "2026-06-02"},
    {"product": "Kolonne Null Non-Alcoholic Riesling", "brand": "Kolonne Null", "category": "Non-alcoholic wine", "abv": "0.0%", "dealcoholized": "Yes", "method": "Vacuum distillation at ~30°C to remove alcohol from base wine", "retailers": "The Zero Proof", "times": "3", "first": "2026-03-06", "recent": "2026-08-12"},
    {"product": "Clearscape Non-Alcoholic Chardonnay", "brand": "Clearscape", "category": "Non-alcoholic wine", "abv": "Not verified", "dealcoholized": "Yes", "method": "Not verified in detail", "retailers": "Total Wine", "times": "3", "first": "2026-03-28", "recent": "2026-08-11"},
    {"product": "Be Free Sauvignon Blanc Non-Alcoholic Wine", "brand": "Be Free", "category": "Non-alcoholic wine", "abv": "Not verified", "dealcoholized": "Yes", "method": 'Dealcoholized ("special method", not detailed)', "retailers": "Total Wine", "times": "3", "first": "2026-03-28", "recent": "2026-08-02"},
    {"product": 'Miguel Torres Non-Alcoholic Sauvignon Blanc "Serena"', "brand": "Familia Torres (Natureo line)", "category": "Non-alcoholic wine", "abv": "Not verified", "dealcoholized": "Yes", "method": 'Torres describes an "improved dealcoholizing technique"; exact process not detailed', "retailers": "Metro Wine & Spirits", "times": "3", "first": "2026-04-19", "recent": "2026-05-29"},
    {"product": "Rondel Zero Cava Rose Non-Alcoholic Wine", "brand": "Rondel", "category": "Non-alcoholic sparkling wine", "abv": "Not verified", "dealcoholized": "Yes", "method": "Not verified", "retailers": "Total Wine", "times": "3", "first": "2026-04-29", "recent": "2026-09-17"},
    {"product": "Wolffer Spring in a Bottle Alcohol Removed Rose Sparkling", "brand": "Wolffer Estate", "category": "Non-alcoholic sparkling wine", "abv": "Not verified", "dealcoholized": "Yes", "method": "Alcohol-removed (branded); method not detailed", "retailers": "Total Wine", "times": "2", "first": "2026-01-11", "recent": "2026-02-28"},
    {"product": "Biagio Cru Non-Alcoholic Rose All Day", "brand": "Biagio Cru", "category": "Non-alcoholic wine", "abv": "Not verified", "dealcoholized": "Yes", "method": "Delicate alcohol removal after early-harvest grapes and low-sugar yeasts (Total Wine product highlights); specific technology not named", "retailers": "Total Wine", "times": "2", "first": "2026-03-06", "recent": "2026-06-21"},
    {"product": "Noughty Dealcoholized Rosé", "brand": "Noughty (Thomson & Scott)", "category": "Non-alcoholic wine", "abv": "<0.5%", "dealcoholized": "Yes", "method": "Gently spun cone technology under vacuum at low temperature", "retailers": "The Zero Proof", "times": "2", "first": "2026-03-06", "recent": "2026-03-22"},
    {"product": "WiesenObst Cider Rosé Non-Alcoholic Cider", "brand": "Jörg Geiger", "category": "Non-alcoholic cider", "abv": "<0.5%", "dealcoholized": "Yes", "method": "Dealcoholized cider made from cider apples, perry pears and dealcoholized red wine, hops, herbs and flowers", "retailers": "Delmosa", "times": "2", "first": "2026-04-25", "recent": "2026-06-23"},
    {"product": "Grad 36° Non-Alcoholic Wine", "brand": "Jörg Geiger", "category": "Non-alcoholic wine", "abv": "<0.5%", "dealcoholized": "Yes", "method": "Blend of dealcoholized red (Grenache) wine (75%) with damson plum, currant and blackberry juice plus herb/wildflower extracts", "retailers": "Delmosa", "times": "2", "first": "2026-04-25", "recent": "2026-06-23"},
    {"product": "Mionetto Non Alcoholic Italy White Wine", "brand": "Mionetto", "category": "Non-alcoholic sparkling wine", "abv": "Not verified", "dealcoholized": "Yes", "method": "Alcohol-removed Prosecco (branded); method not detailed", "retailers": "Harris Teeter (Instacart)", "times": "1", "first": "2025-09-22", "recent": "2025-09-22"},
    {"product": "Crodino Non-Alcoholic Spritz", "brand": "Crodino (Campari Group)", "category": "Non-alcoholic aperitifs", "abv": "Not verified", "dealcoholized": "No (formulated)", "method": "Produced as a non-alcoholic bitter aperitif since 1964; never an alcoholic product that was dealcoholized", "retailers": "Metro Wine & Spirits", "times": "1", "first": "2025-10-03", "recent": "2025-10-03"},
    {"product": '90+ Cellars Alcohol-Removed Sparkling Brut "Lot 230"', "brand": "90+ Cellars", "category": "Non-alcoholic sparkling wine", "abv": "<0.5%", "dealcoholized": "Yes", "method": "Traditionally made, then alcohol removed via reverse osmosis", "retailers": "Metro Wine & Spirits", "times": "1", "first": "2025-10-03", "recent": "2025-10-03"},
    {"product": 'Lyre\'s "Amalfi Spritz" Non-Alcoholic Canned Cocktail', "brand": "Lyre's", "category": "Non-alcoholic cocktails/RTDs", "abv": "<0.3%", "dealcoholized": "No (formulated)", "method": "Crafted from natural essences/extracts, not dealcoholized", "retailers": "Metro Wine & Spirits", "times": "1", "first": "2025-10-03", "recent": "2025-10-03"},
    {"product": "Best Day Brewing Kölsch Non-Alcoholic Beer", "brand": "Best Day Brewing", "category": "Non-alcoholic beer", "abv": "<0.5%", "dealcoholized": "Yes", "method": "Traditionally brewed, then alcohol gently removed post-fermentation (Paste Magazine brewery-briefed review); specific technology not named", "retailers": "Best Day Brewing", "times": "1", "first": "2025-10-10", "recent": "2025-10-10"},
    {"product": "HOP WTR Sparkling Hop Water, Blood Orange, Non-Alcoholic", "brand": "HOP WTR", "category": "Non-alcoholic beer", "abv": "0%", "dealcoholized": "No (formulated)", "method": "Hop-flavored sparkling water; not a brewed/fermented beer", "retailers": "Giant Food (Instacart)", "times": "1", "first": "2025-10-15", "recent": "2025-10-15"},
    {"product": "Original Sin Non-Alcoholic Cider Mix Pack (Golden/White/Dragon Widow)", "brand": "Original Sin Cider", "category": "Non-alcoholic cider", "abv": "Not verified", "dealcoholized": "No (formulated)", "method": "Made with apple cider vinegar and fruit juice, not from a dealcoholized alcoholic cider", "retailers": "Metro Wine & Spirits", "times": "1", "first": "2025-10-21", "recent": "2025-10-21"},
    {"product": 'St. Agrestis "Phony Negroni" Non-Alcoholic Negroni Cocktail', "brand": "St. Agrestis", "category": "Non-alcoholic cocktails/RTDs", "abv": "0%", "dealcoholized": "No (formulated)", "method": 'Company states explicitly: process maintains flavor "and not with dealcoholizing"', "retailers": "Metro Wine & Spirits", "times": "1", "first": "2025-10-21", "recent": "2025-10-21"},
    {"product": "French Bloom Le Blanc Non-Alcoholic Sparkling Wine", "brand": "French Bloom", "category": "Non-alcoholic sparkling wine", "abv": "0%", "dealcoholized": "Yes", "method": "Organic French Chardonnay wine that is dealcoholized, then blended with spring water, grape juice and organic lemon juice", "retailers": "The Zero Proof", "times": "1", "first": "2025-11-18", "recent": "2025-11-18"},
    {"product": "Lapo's Non-Alcoholic Negroni", "brand": "Lapo's", "category": "Non-alcoholic cocktails/RTDs", "abv": "0.0%", "dealcoholized": "No (formulated)", "method": "Canned Italian-style negroni alternative; marketed as 0.0% ABV zero-proof cocktail (not dealcoholized)", "retailers": "The Zero Proof", "times": "1", "first": "2025-11-20", "recent": "2025-11-20"},
    {"product": "Kolonne Null Non-Alcoholic Rosé", "brand": "Kolonne Null", "category": "Non-alcoholic wine", "abv": "0.0%", "dealcoholized": "Yes", "method": "Vacuum distillation at ~30°C to remove alcohol from base wine", "retailers": "The Zero Proof", "times": "1", "first": "2025-11-20", "recent": "2025-11-20"},
    {"product": "Go Brewing Sunbeam Pils Non-Alcoholic (Brew Non-Alcoholic Sunbeam Pils with German Malt & Hops)", "brand": "Go Brewing", "category": "Non-alcoholic beer", "abv": "<0.5% (producer)", "dealcoholized": "No (limited fermentation)", "method": "Producer FAQ: proprietary brewing keeps the beer naturally under 0.5% ABV without dilution or dealcoholization", "retailers": "Giant Food (Instacart)", "times": "1", "first": "2025-12-04", "recent": "2025-12-04"},
    {"product": "Mingle Non-Alcoholic Sparkling Raspberry Rose", "brand": "Mingle", "category": "Non-alcoholic cocktails/RTDs", "abv": "0.00% (retail listing)", "dealcoholized": "No (formulated)", "method": "Formulated mocktail — juice, sparkling water, and botanicals; never an alcoholic cocktail", "retailers": "Total Wine", "times": "1", "first": "2026-01-11", "recent": "2026-01-11"},
    {"product": 'Untitled Art "FLVR!" Non-Alcoholic Sour Ale w/ Mango & Dragonfruit', "brand": "Untitled Art Brewing", "category": "Non-alcoholic beer", "abv": "<0.5% (producer)", "dealcoholized": "Yes", "method": "Reverse osmosis membrane filtration after full fermentation", "retailers": "Metro Wine & Spirits", "times": "1", "first": "2026-01-23", "recent": "2026-01-23"},
    {"product": 'Asahi "Dry" Non-Alcoholic', "brand": "Asahi Breweries", "category": "Non-alcoholic beer", "abv": "0.00%", "dealcoholized": "No (formulated)", "method": "Wort-free complete formulation method — unfermented wort blended with flavor compounds (incl. MBT); not dealcoholized", "retailers": "Metro Wine & Spirits", "times": "1", "first": "2026-01-28", "recent": "2026-01-28"},
    {"product": "Nonny Czech Pilsner Non-Alcoholic Beer", "brand": "Nonny Beer", "category": "Non-alcoholic beer", "abv": "<0.5% (producer)", "dealcoholized": "No (formulated)", "method": "Brewed to stay below 0.5%; producer pages have no alcohol-removal language", "retailers": "Upside Drinks", "times": "1", "first": "2026-02-18", "recent": "2026-02-18"},
    {"product": "Grolsch 0.0% Non-Alcoholic Pilsner", "brand": "Grolsch", "category": "Non-alcoholic beer", "abv": "0.0%", "dealcoholized": "No (special yeast)", "method": "Producer: special yeast and full fermentation that produces no alcohol — not a removal process", "retailers": "Upside Drinks", "times": "1", "first": "2026-02-18", "recent": "2026-02-18"},
    {"product": "Pierre Zero Non-Alcoholic Rosé (Bag-in-Box)", "brand": "Pierre Zero", "category": "Non-alcoholic wine", "abv": "<0.5%", "dealcoholized": "Yes", "method": 'French brand marketed as "Zero" alcohol-removed wine; specific technique not independently verified', "retailers": "Upside Drinks", "times": "1", "first": "2026-02-18", "recent": "2026-02-18"},
    {"product": "JP. Chenet Non-Alcoholic Sparkling Rosé", "brand": "JP. Chenet", "category": "Non-alcoholic sparkling wine", "abv": "<0.5%", "dealcoholized": "Yes", "method": "Made from dealcoholized rosé wine plus rectified grape must concentrate, per ingredient list", "retailers": "Upside Drinks", "times": "1", "first": "2026-02-18", "recent": "2026-02-18"},
    {"product": 'Christian Drouin "Jus de Poire Petillant" Non-Alcoholic Pear Cider', "brand": "Christian Drouin", "category": "Non-alcoholic cider", "abv": "Not verified", "dealcoholized": "No (never fermented)", "method": "Pressed pear juice with carbonation — sparkling pear juice, not a dealcoholized cider (producer)", "retailers": "Metro Wine & Spirits", "times": "1", "first": "2026-02-22", "recent": "2026-02-22"},
    {"product": "Untitled Art N/A FLVR! Italian-Style Pils", "brand": "Untitled Art Brewing", "category": "Non-alcoholic beer", "abv": "<0.5% (producer)", "dealcoholized": "Yes", "method": "Reverse osmosis membrane filtration after full fermentation", "retailers": "Metro Wine & Spirits", "times": "1", "first": "2026-03-04", "recent": "2026-03-04"},
    {"product": "Gruvi Non-Alcoholic Sangria", "brand": "Grüvi", "category": "Non-alcoholic cocktails/RTDs", "abv": "<0.5% (producer)", "dealcoholized": "Yes", "method": "Dealcoholized California red wine blended with natural fruit extracts (producer)", "retailers": "Total Wine", "times": "1", "first": "2026-03-24", "recent": "2026-03-24"},
    {"product": "Giesen Non-Alcoholic Sauvignon Blanc", "brand": "Giesen", "category": "Non-alcoholic wine", "abv": "≤0.5%", "dealcoholized": "Yes", "method": "Spinning cone technology", "retailers": "Metro Wine & Spirits", "times": "1", "first": "2026-04-09", "recent": "2026-04-09"},
    {"product": 'Eric Bordelet "Jus de Pommes A Sydre Perlant" Non-Alcoholic French Cider', "brand": "Eric Bordelet", "category": "Non-alcoholic cider", "abv": "Not verified", "dealcoholized": "No (never fermented)", "method": "Pressed cider-apple juice with added CO2 — never fermented, so no alcohol to remove", "retailers": "Metro Wine & Spirits", "times": "1", "first": "2026-04-19", "recent": "2026-04-19"},
    {"product": "King Maui 0% Non-Alcoholic Marlborough Sauvignon Blanc", "brand": "King Maui", "category": "Non-alcoholic wine", "abv": "Not verified", "dealcoholized": "Yes", "method": "Not verified", "retailers": "Total Wine", "times": "1", "first": "2026-04-22", "recent": "2026-04-22"},
    {"product": "ViSecco Pinot Meunier Non-Alcoholic Sparkling Red", "brand": "Jörg Geiger", "category": "Non-alcoholic sparkling wine", "abv": "Not verified (brand line typically <0.5%)", "dealcoholized": "Yes", "method": "Not independently verified for this specific SKU", "retailers": "Delmosa", "times": "1", "first": "2026-04-25", "recent": "2026-04-25"},
    {"product": "Blanc de Blanc Non-Alcoholic Sparkling Wine (Delmosa)", "brand": "Jörg Geiger", "category": "Non-alcoholic sparkling wine", "abv": "<0.5%", "dealcoholized": "Yes", "method": 'Organic Chardonnay/Colombard wine fermented and aged on lees for two years, then "gently dealcoholized" (specific technique not detailed)', "retailers": "Delmosa", "times": "1", "first": "2026-04-25", "recent": "2026-04-25"},
    {"product": "Nozeco Alcohol Free Brut Rose", "brand": "Nozeco", "category": "Non-alcoholic sparkling wine", "abv": "Not verified", "dealcoholized": "Yes", "method": "Not verified", "retailers": "Total Wine", "times": "1", "first": "2026-04-29", "recent": "2026-04-29"},
    {"product": 'Ollivier Cottenceau Phénomène Non-Alcoholic "Muscadet"', "brand": "Ollivier Cottenceau (Domaine de la Grenaudière)", "category": "Non-alcoholic wine", "abv": "<0.5% (retail listings)", "dealcoholized": "Yes", "method": "Melon de Bourgogne vinified conventionally then dealcoholized (producer range described as vin désalcoolisé)", "retailers": "Boisson", "times": "1", "first": "2026-05-02", "recent": "2026-05-02"},
    {"product": "Domaine de Montrose Non-Alcoholic Rosé", "brand": "Domaine de Montrose", "category": "Non-alcoholic wine", "abv": "<0.5% (retail listings)", "dealcoholized": "Yes", "method": "Grenache and Cinsault rosé fermented conventionally then dealcoholized (vin désalcoolisé)", "retailers": "Boisson", "times": "1", "first": "2026-05-02", "recent": "2026-05-02"},
    {"product": "Tired Hands Non-Alcoholic N/Alien Church", "brand": "Tired Hands", "category": "Non-alcoholic beer", "abv": "<0.5% (retail listing)", "dealcoholized": "No (special yeast)", "method": "Brewed with experimental yeast and the Alien Church hop bill; brewery menu describes no post-brew alcohol-removal step", "retailers": "Total Wine", "times": "1", "first": "2026-05-22", "recent": "2026-05-22"},
    {"product": 'Valckenberg "Zero" Non-Alcoholic Riesling', "brand": "P.J. Valckenberg", "category": "Non-alcoholic wine", "abv": "Not verified", "dealcoholized": "Yes", "method": "Not verified", "retailers": "Metro Wine & Spirits", "times": "1", "first": "2026-05-24", "recent": "2026-05-24"},
    {"product": 'Valckenberg "Zero" Non-Alcoholic Sparkling', "brand": "P.J. Valckenberg", "category": "Non-alcoholic sparkling wine", "abv": "Not verified", "dealcoholized": "Yes", "method": "Not verified", "retailers": "Metro Wine & Spirits", "times": "1", "first": "2026-05-24", "recent": "2026-05-24"},
    {"product": 'Erdinger Weissbrau N/A Lager "Alkoholfrei"', "brand": "Erdinger Weißbräu", "category": "Non-alcoholic beer", "abv": "<0.5%", "dealcoholized": "Not verified", "method": "Brewed under Bavarian Purity Law; specific alcohol-removal method not disclosed", "retailers": "Metro Wine & Spirits", "times": "1", "first": "2026-05-29", "recent": "2026-05-29"},
    {"product": "Missing Thorn Non-Alcoholic Still White", "brand": "Missing Thorn", "category": "Non-alcoholic wine", "abv": "Not verified", "dealcoholized": "Yes", "method": "Not independently verified; brand markets wines as alcohol-removed by winemaker Aaron Pott", "retailers": "The Zero Proof", "times": "1", "first": "2026-06-02", "recent": "2026-06-02"},
    {"product": 'Josef Leitz Non-Alcoholic Sparkling Rosé "Eins Zwei Zero"', "brand": "Weingut Leitz", "category": "Non-alcoholic sparkling wine", "abv": "0%", "dealcoholized": "Yes", "method": "Vacuum distillation at low temperature", "retailers": "The Zero Proof", "times": "1", "first": "2026-06-02", "recent": "2026-06-02"},
    {"product": "Athletic Brewing Co. Athletic ESB Non-Alcoholic Beer (Limited Edition)", "brand": "Athletic Brewing Company", "category": "Non-alcoholic beer", "abv": "<0.5% (retail listing)", "dealcoholized": "No (proprietary NA brewing)", "method": "Proprietary process built for NA from the start; Fast Company (2025) and Popular Mechanics quote founders saying it is neither dealcoholization of finished beer nor simple arrested fermentation", "retailers": "Minus Moonshine", "times": "1", "first": "2026-06-02", "recent": "2026-06-02"},
    {"product": "De Nada Non-Alcoholic Rosé", "brand": "De Nada (Paumanok Vineyards)", "category": "Non-alcoholic wine", "abv": "<0.5% (retail listing)", "dealcoholized": "Yes", "method": "Alcohol removed from conventionally vinified Chilean rosé (Maule Valley; collaboration with Juan Esteban Sepulveda)", "retailers": "Minus Moonshine", "times": "1", "first": "2026-06-02", "recent": "2026-06-02"},
    {"product": 'Josef Leitz Non-Alcoholic Sparkling Blanc de Blancs "Eins Zwei Zero"', "brand": "Weingut Leitz", "category": "Non-alcoholic sparkling wine", "abv": "0%", "dealcoholized": "Yes", "method": "Vacuum distillation at low temperature", "retailers": "Metro Wine & Spirits", "times": "1", "first": "2026-06-24", "recent": "2026-06-24"},
    {"product": "Freixenet Non-Alcoholic Sparkling", "brand": "Freixenet", "category": "Non-alcoholic sparkling wine", "abv": "Not verified", "dealcoholized": "Yes", "method": 'Branded "Alcohol Removed"; specific method not detailed', "retailers": "Metro Wine & Spirits", "times": "1", "first": "2026-06-24", "recent": "2026-06-24"},
    {"product": 'Fio Non-Alcoholic Riesling "Fabelhaft"', "brand": "Fio", "category": "Non-alcoholic wine", "abv": "<0.5% (importer listing)", "dealcoholized": "Yes", "method": "Alcohol removed from stainless-steel-fermented Mosel Riesling (importer)", "retailers": "Metro Wine & Spirits", "times": "1", "first": "2026-07-04", "recent": "2026-07-04"},
    {"product": "Ritual Zero Proof Tequila Alternative", "brand": "Ritual Zero Proof", "category": "Non-alcoholic spirits", "abv": "0%", "dealcoholized": "No (formulated)", "method": "Formulated agave spirit alternative, never distilled from alcohol", "retailers": "Metro Wine & Spirits", "times": "1", "first": "2026-07-04", "recent": "2026-07-04"},
    {"product": 'P.J. Valckenberg "It\'s Not a Sin" NA Ginger/Bitter Orange Peel Wine', "brand": "P.J. Valckenberg", "category": "Non-alcoholic aperitifs", "abv": "Not verified", "dealcoholized": "No (formulated)", "method": "Formulated botanical beverage — no underlying alcoholic drink before removal", "retailers": "InternetWines.com", "times": "1", "first": "2026-07-26", "recent": "2026-07-26"},
    {"product": "Sea Monster Tidal Wave White Alcohol Removed Wine", "brand": "Sea Monster", "category": "Non-alcoholic wine", "abv": "Not verified", "dealcoholized": "Yes", "method": 'Branded "Alcohol Removed"; specific method not detailed', "retailers": "InternetWines.com", "times": "1", "first": "2026-07-26", "recent": "2026-07-26"},
    {"product": "Butter Zero Sparkling Rosé Non-Alcoholic", "brand": "Butter Zero", "category": "Non-alcoholic sparkling wine", "abv": "<0.5% (retail listing)", "dealcoholized": "Yes", "method": "Alcohol removed from conventionally vinified California sparkling rosé (producer launch)", "retailers": "InternetWines.com", "times": "1", "first": "2026-07-26", "recent": "2026-07-26"},
    {"product": "Mount Fishtail Zero Sauvignon Blanc", "brand": "Mount Fishtail", "category": "Non-alcoholic wine", "abv": "Not verified", "dealcoholized": "Yes", "method": 'Branded "Alcohol Removed"; specific method not detailed', "retailers": "InternetWines.com", "times": "1", "first": "2026-07-26", "recent": "2026-07-26"},
    {"product": "Butter Zero Pinot Noir Non-Alcoholic", "brand": "Butter Zero", "category": "Non-alcoholic wine", "abv": "<0.5% (retail listing)", "dealcoholized": "Yes", "method": "Alcohol removed from conventionally vinified Pinot Noir (producer launch)", "retailers": "InternetWines.com", "times": "1", "first": "2026-07-26", "recent": "2026-07-26"},
    {"product": "Zolo Zero Malbec Rosé Non-Alcoholic Wine", "brand": "Zolo", "category": "Non-alcoholic wine", "abv": "0% (importer tech sheet)", "dealcoholized": "Yes", "method": "96% dealcoholized by proprietary technique plus 4% grape juice (Vino del Sol tech sheet)", "retailers": "InternetWines.com", "times": "1", "first": "2026-07-26", "recent": "2026-07-26"},
    {"product": "Butter Zero Chardonnay Non-Alcoholic", "brand": "Butter Zero", "category": "Non-alcoholic wine", "abv": "<0.5% (producer launch)", "dealcoholized": "Yes", "method": "Alcohol removed from conventionally vinified Chardonnay (producer launch)", "retailers": "InternetWines.com", "times": "1", "first": "2026-07-26", "recent": "2026-07-26"},
    {"product": "Chloe Alcohol-Removed Pinot Grigio", "brand": "Chloe Wine Collection", "category": "Non-alcoholic wine", "abv": "Not verified", "dealcoholized": "Yes", "method": "Alcohol-removed (branded); method not detailed", "retailers": "Total Wine", "times": "1", "first": "2026-07-27", "recent": "2026-07-27"},
    {"product": "Chateau Diana Zero Non-Alcoholic California White Wine Blend", "brand": "Chateau Diana", "category": "Non-alcoholic wine", "abv": "Not verified", "dealcoholized": "Yes", "method": 'Branded "Alcohol Removed"; specific method not detailed', "retailers": "Walmart", "times": "1", "first": "2026-08-08", "recent": "2026-08-08"},
    {"product": "Chateau Diana Zero Non-Alcoholic California Rosé", "brand": "Chateau Diana", "category": "Non-alcoholic wine", "abv": "Not verified", "dealcoholized": "Yes", "method": 'Branded "Alcohol Removed"; specific method not detailed', "retailers": "Walmart", "times": "1", "first": "2026-08-08", "recent": "2026-08-08"},
    {"product": "Penn's Best Non-Alcoholic Lager", "brand": "Penn's Best", "category": "Non-alcoholic beer", "abv": "Not verified", "dealcoholized": "Not verified", "method": "Not verified", "retailers": "Total Wine", "times": "1", "first": "2026-08-11", "recent": "2026-08-11"},
    {"product": "Ariel Non-Alcoholic Chardonnay", "brand": "Ariel (Ariel Vineyards / J. Lohr)", "category": "Non-alcoholic wine", "abv": "Not verified", "dealcoholized": "Yes", "method": 'Marketed as "Dealcoholized Wine"; specific method not detailed', "retailers": "Metro Wine & Spirits", "times": "1", "first": "2026-08-22", "recent": "2026-08-22"},
    {"product": 'Greenbar "UNRum + Cola" Non-Alcoholic Canned Cocktail', "brand": "Greenbar", "category": "Non-alcoholic cocktails/RTDs", "abv": "<0.5% (producer)", "dealcoholized": "Yes", "method": "Alcohol boiled off after distillation and infusion (producer spec sheet)", "retailers": "Metro Wine & Spirits", "times": "1", "first": "2026-08-28", "recent": "2026-08-28"},
    {"product": 'Flying Dog "Deep Fake" Non-Alcoholic IPA', "brand": "Flying Dog Brewery", "category": "Non-alcoholic beer", "abv": "Not verified", "dealcoholized": "Not verified", "method": "Not verified", "retailers": "Metro Wine & Spirits", "times": "1", "first": "2026-08-28", "recent": "2026-08-28"},
    {"product": 'Untitled Art "FLVR!" Non-Alcoholic Juicy IPA', "brand": "Untitled Art Brewing", "category": "Non-alcoholic beer", "abv": "<0.5% (producer)", "dealcoholized": "Yes", "method": "Reverse osmosis membrane filtration after full fermentation", "retailers": "Metro Wine & Spirits", "times": "1", "first": "2026-09-05", "recent": "2026-09-05"},
    {"product": 'Pure Project "Grounded" Non-Alcoholic IPA', "brand": "Pure Project", "category": "Non-alcoholic beer", "abv": "Not verified", "dealcoholized": "Not verified", "method": "Not verified", "retailers": "Metro Wine & Spirits", "times": "1", "first": "2026-09-05", "recent": "2026-09-05"},
    {"product": 'Birrificio Baladin "Passione In Rosso" Non-Alcoholic Italian Aperitivo', "brand": "Birrificio Baladin", "category": "Non-alcoholic aperitifs", "abv": "0.0% (producer)", "dealcoholized": "No (formulated)", "method": "Formulated aperitivo — water, cane sugar, and natural flavors; no underlying alcoholic drink", "retailers": "Metro Wine & Spirits", "times": "1", "first": "2026-09-05", "recent": "2026-09-05"},
    {"product": 'Spiritless "Kentucky 74" Non-Alcoholic Whiskey Spirit', "brand": "Spiritless", "category": "Non-alcoholic spirits", "abv": "Not verified (FDA non-alcoholic, <0.5% typical)", "dealcoholized": "Yes", "method": 'Proprietary "reverse distillation" — real bourbon-derived distillate with ethanol removed', "retailers": "Metro Wine & Spirits", "times": "1", "first": "2026-09-05", "recent": "2026-09-05"},
    {"product": "Deschutes Non-Alcoholic Fresh Squeezed IPA", "brand": "Deschutes Brewery", "category": "Non-alcoholic beer", "abv": "<0.5% (producer)", "dealcoholized": "Yes", "method": "Reverse osmosis (BrewVo process via Sustainable Beverage Technologies), then secondary cold fermentation and dry-hopping", "retailers": "Deschutes Brewery", "times": "1", "first": "2026-09-06", "recent": "2026-09-06"},
    {"product": "Spiritless Jalisco 55 Non-Alcoholic Reposado Tequila", "brand": "Spiritless", "category": "Non-alcoholic spirits", "abv": "0.0%", "dealcoholized": "Yes", "method": "Reverse distillation (same documented method as Spiritless Kentucky 74)", "retailers": "InternetWines.com", "times": "1", "first": "2026-09-14", "recent": "2026-09-14"},
    {"product": "St. Regis Non-Alcoholic Rose", "brand": "St. Regis", "category": "Non-alcoholic wine", "abv": "<0.5% (retailer)", "dealcoholized": "Yes", "method": "Vacuum distillation of fermented and aged Syrah/Shiraz rosé (producer about page)", "retailers": "Total Wine", "times": "1", "first": "2026-09-17", "recent": "2026-09-17"},
]

# Published cellar reviews that are not in the purchase table.
PUBLISHED_ONLY: list[dict[str, str]] = [
    {"product": "Leitz Eins-Zwei-Zero Riesling", "brand": "Leitz", "category": "Non-alcoholic wine", "abv": "<0.1% vol (producer)", "dealcoholized": "Yes", "method": "Vacuum distillation", "retailers": "", "times": "0", "first": "", "recent": ""},
    {"product": "Guinness 0.0", "brand": "Guinness", "category": "Non-alcoholic beer", "abv": "Marketed as 0.0%", "dealcoholized": "Yes", "method": "Cold filtration after a conventional Guinness brew", "retailers": "", "times": "0", "first": "", "recent": ""},
    {"product": "Thomson & Scott Noughty Sparkling Chardonnay", "brand": "Thomson & Scott", "category": "Non-alcoholic sparkling wine", "abv": "0.0% vol on producer technical data", "dealcoholized": "Yes", "method": "Vacuum distillation with aroma recovery", "retailers": "", "times": "0", "first": "", "recent": ""},
    {"product": "Lyre's Italian Orange", "brand": "Lyre's", "category": "Non-alcoholic aperitifs", "abv": "<0.3% ABV", "dealcoholized": "No (formulated)", "method": "Formulated as a zero-proof alternative", "retailers": "", "times": "0", "first": "", "recent": ""},
    {"product": "Oddbird Blanc de Blancs", "brand": "Oddbird", "category": "Non-alcoholic sparkling wine", "abv": "", "dealcoholized": "Yes", "method": "", "retailers": "", "times": "0", "first": "", "recent": ""},
]

SITE_CATEGORY = {
    "Non-alcoholic wine": "wine",
    "Non-alcoholic sparkling wine": "wine",
    "Non-alcoholic beer": "beer",
    "Non-alcoholic cocktails/RTDs": "cocktails",
    "Non-alcoholic cider": "cider",
    "Non-alcoholic aperitifs": "cocktails",
    "Non-alcoholic spirits": "spirits",
}

# Purchase-table products that already have a published review. Do not create another.
ALREADY_REVIEWED = {
    ("giesen", "giesen non-alcoholic sauvignon blanc"),
    ("giesen", "0% sauvignon blanc"),
    ("freixenet", "freixenet non-alcoholic sparkling"),
    ("freixenet", "0,0 sparkling white"),
    ("spiritless", 'spiritless "kentucky 74" non-alcoholic whiskey spirit'),
    ("spiritless", "kentucky 74"),
    ("st. regis", "st. regis non-alcoholic rose"),
    ("st. regis", "non-alcoholic rosé"),
}

# Existing queue rows to keep, keyed by a normalized match token.
EXISTING_QUEUE = [
    {
        "product": "Athletic Brewing Run Wild IPA",
        "brand": "Athletic Brewing",
        "category": "beer",
        "priority": "high",
        "status": "queued",
        "notes": "Proprietary NA brewing. Founders told Fast Company (2025) and Popular Mechanics the process is neither dealcoholization of finished beer nor simple arrested fermentation. Mark Dealcoholized: No.",
    },
    {
        "product": "Sober Spirits Whisky",
        "brand": "Sober Spirits",
        "category": "spirits",
        "priority": "high",
        "status": "queued",
        "notes": "Confirm the product exists, the exact SKU, ABV, and whether it is dealcoholized or formulated.",
    },
    {
        "product": "Heineken 0.0",
        "brand": "Heineken",
        "category": "beer",
        "priority": "normal",
        "status": "queued",
        "notes": "Widely available. Confirm dealcoholization method from Heineken technical communications.",
    },
]


def norm(value: str) -> str:
    return " ".join(value.lower().replace("é", "e").replace("ö", "o").replace("ü", "u").replace("ß", "ss").replace('"', "").replace("'", "").split())


def already_reviewed(row: dict[str, str]) -> bool:
    brand = norm(row["brand"]).split("(")[0].strip()
    product = norm(row["product"])
    keys = {
        (brand, product),
        (norm(row["brand"]), product),
    }
    if "sauvignon blanc" in product and brand.startswith("giesen"):
        return True
    if product == "freixenet non-alcoholic sparkling":
        return True
    if "kentucky 74" in product and "spiritless" in brand:
        return True
    if "st. regis" in brand and "rose" in product and "sparkling" not in product:
        return True
    return bool(keys & ALREADY_REVIEWED)


def dealcoholized_token(value: str) -> str:
    lowered = value.lower().strip()
    if lowered.startswith("not"):
        return "not-verified"
    if lowered.startswith("yes"):
        return "yes"
    if lowered.startswith("no"):
        return "no"
    return "not-verified"


def load_existing_skus() -> dict[str, str]:
    path = ROOT / "master-products.csv"
    if not path.is_file():
        return {}

    with path.open(newline="", encoding="utf-8") as handle:
        reader = csv.DictReader(handle)
        if not reader.fieldnames or "Product" not in reader.fieldnames:
            return {}
        return {
            row["Product"]: row.get("ID") or ""
            for row in reader
            if row.get("Product") and row.get("ID")
        }


def next_sku_number(existing: dict[str, str]) -> int:
    numbers = []
    for sku in existing.values():
        if sku.startswith("TDS-") and sku[4:].isdigit():
            numbers.append(int(sku[4:]))
    return max(numbers, default=0) + 1


def assign_skus(rows: list[dict[str, str]]) -> dict[str, str]:
    existing = load_existing_skus()
    next_number = next_sku_number(existing)
    by_product: dict[str, str] = {}

    for row in rows:
        sku = existing.get(row["product"])
        if not sku:
            sku = f"TDS-{next_number:04d}"
            next_number += 1
        row["id"] = sku
        row["ean"] = SOURCED_EANS.get(row["product"], "")
        by_product[row["product"]] = sku

    return by_product


def sku_for(product: str, by_product: dict[str, str]) -> str:
    if product in by_product:
        return by_product[product]
    alias = SKU_ALIASES.get(product)
    if alias and alias in by_product:
        return by_product[alias]
    return EXTRA_SKUS[product]


def priority_for(times: int) -> str:
    if times >= 8:
        return "high"
    if times >= 3:
        return "normal"
    return "low"


def yaml_scalar(value: str) -> str:
    if value == "":
        return '""'
    if any(ch in value for ch in [":", "#", "{", "}", "[", "]", ",", "&", "*", "!", "|", ">", "'", '"', "%", "@", "`"]) or value != value.strip():
        return '"' + value.replace("\\", "\\\\").replace('"', '\\"') + '"'
    return value


def dump_item(item: dict[str, object]) -> str:
    lines = ["- product: " + yaml_scalar(str(item["product"]))]
    order = [
        "id",
        "ean",
        "brand",
        "category",
        "priority",
        "status",
        "dealcoholized",
        "method",
        "abv",
        "seen_at",
        "times_purchased",
        "first_seen",
        "last_seen",
        "notes",
    ]
    for key in order:
        if key not in item or item[key] in (None, ""):
            continue
        value = item[key]
        if key == "times_purchased":
            lines.append(f"  {key}: {int(value)}")
            continue
        lines.append(f"  {key}: {yaml_scalar(str(value))}")
    return "\n".join(lines)


def write_csv(rows: list[dict[str, str]]) -> None:
    path = ROOT / "master-products.csv"
    with path.open("w", newline="", encoding="utf-8") as handle:
        writer = csv.writer(handle, lineterminator="\n")
        writer.writerow(HEADERS)
        for row in rows:
            writer.writerow([
                row["id"],
                row["ean"],
                row["product"],
                row["brand"],
                row["category"],
                row["abv"],
                row["dealcoholized"],
                row["method"],
                row["retailers"],
                row["times"],
                row["first"],
                row["recent"],
            ])


def write_queue(purchased: list[dict[str, str]], by_product: dict[str, str]) -> tuple[int, int]:
    items: list[dict[str, object]] = []
    added = 0
    skipped = 0

    for item in EXISTING_QUEUE:
        queued = dict(item)
        code = sku_for(str(item["product"]), by_product)
        queued["id"] = code
        ean = SOURCED_EANS.get(str(item["product"]), "")
        if ean:
            queued["ean"] = ean
        items.append(queued)

    for row in purchased:
        if already_reviewed(row):
            skipped += 1
            continue

        times = int(row["times"])
        item = {
            "product": row["product"],
            "id": row["id"],
            "ean": row["ean"],
            "brand": row["brand"],
            "category": SITE_CATEGORY[row["category"]],
            "priority": priority_for(times),
            "status": "queued",
            "dealcoholized": dealcoholized_token(row["dealcoholized"]),
            "method": row["method"],
            "abv": row["abv"],
            "seen_at": row["retailers"],
            "times_purchased": times,
            "first_seen": row["first"],
            "last_seen": row["recent"],
            "notes": (
                f"From the master product table. Purchased {times} time(s) "
                f"between {row['first']} and {row['recent']}. "
                "Do not publish until ABV and dealcoholization are sourced."
            ),
        }
        items.append(item)
        added += 1

    # Keep published markers for existing reviews so OpenClaw does not duplicate them.
    items.extend([
        {
            "product": "Giesen 0% Sauvignon Blanc",
            "id": sku_for("Giesen 0% Sauvignon Blanc", by_product),
            "brand": "Giesen",
            "category": "wine",
            "priority": "normal",
            "status": "published",
            "notes": "Already published at reviews/wine/giesen-0-sauvignon-blanc/. Same SKU as Giesen Non-Alcoholic Sauvignon Blanc in the master table.",
        },
        {
            "product": "Oddbird Blanc de Blancs",
            "id": sku_for("Oddbird Blanc de Blancs", by_product),
            "brand": "Oddbird",
            "category": "wine",
            "priority": "normal",
            "status": "published",
            "notes": "Already published at reviews/wine/oddbird-blanc-de-blancs/. The Oddbird bundle in the master table is a different SKU.",
        },
        {
            "product": "Freixenet 0.0",
            "id": sku_for("Freixenet 0.0", by_product),
            "brand": "Freixenet",
            "category": "wine",
            "priority": "normal",
            "status": "published",
            "notes": "Already published at reviews/wine/freixenet-0-0/ as Freixenet 0,0 Sparkling White. Sparkling Rosé is a separate queue item.",
        },
        {
            "product": "Spiritless Kentucky 74",
            "id": sku_for("Spiritless Kentucky 74", by_product),
            "brand": "Spiritless",
            "category": "spirits",
            "priority": "normal",
            "status": "published",
            "dealcoholized": "yes",
            "method": "Proprietary reverse distillation of an oak-extracted high-proof spirit",
            "seen_at": "Metro Wine & Spirits",
            "times_purchased": 1,
            "first_seen": "2026-09-05",
            "last_seen": "2026-09-05",
            "notes": "Already published at reviews/spirits/spiritless-kentucky-74/. Do not write a second review.",
        },
        {
            "product": "St. Regis Non-Alcoholic Rosé",
            "id": sku_for("St. Regis Non-Alcoholic Rosé", by_product),
            "brand": "St. Regis",
            "category": "wine",
            "priority": "high",
            "status": "published",
            "dealcoholized": "yes",
            "method": "Vacuum distillation of fermented and aged Syrah/Shiraz rosé",
            "abv": "<0.5% (retailer)",
            "seen_at": "Total Wine",
            "times_purchased": 1,
            "first_seen": "2026-09-17",
            "last_seen": "2026-09-17",
            "notes": "Already published at reviews/wine/st-regis-non-alcoholic-rose/.",
        },
    ])

    path = ROOT / "review-queue.yaml"
    path.write_text("\n\n".join(dump_item(item) for item in items) + "\n", encoding="utf-8")
    return added, skipped


def main() -> None:
    names = [row["product"] for row in PURCHASED]
    if len(names) != len(set(names)):
        raise SystemExit("Duplicate product names in the purchase table.")

    rows = PURCHASED + PUBLISHED_ONLY
    by_product = assign_skus(rows)
    write_csv(rows)
    added, skipped = write_queue(PURCHASED, by_product)
    print(f"Wrote {len(rows)} master rows; queued {added} new reviews; skipped {skipped} already reviewed.")


if __name__ == "__main__":
    main()
