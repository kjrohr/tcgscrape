## Scraping Tool

### TODOs

```
Find solution to sets that TCGplayer doesn't have listings for.
Current thought use Quiet Speculation.
Could also change the str_replace that I'm using to leave dashes.
This will make me change most of the pricing functionality.
```

```
Foil Sell Prices might need to be adjusted and put through the helper function.
This means all files need to change...

```

### Potential Disclosures

```

Ultimately this is a tool to help you automate your card pricing. If TCGPlayer's price guide doesn't have a listing for the card currently we are setting prices to 0. We can change this number at your whim. We still encourage you to run a check against your intentory in Crystal Commerce, max sell price being 0 and min qty being 1. This will show you what slipped through the cracks and you can adjust accordingly.

Manual price adjustments need to be removed otherwise our price adjustments will be affected by them.


```