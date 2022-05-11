# TYPO3 Extension `recordlist_thumbnail`

This extension brings back the thumbnails in the list view which have been dropped with TYPO3 11.5. 

[#92118 - TCA ctrl thumbail setting dropped](https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/11.0/Breaking-92118-TCACtrlThumbailSettingDropped.html?highlight=thumbnail) for more information.

## Usage & Configuration

Install this extension by using `composer req studiomitte/recordlist-thumbnail`.

Configure the extension in the extension settings by providing the table and the field which holds the media. Example is:

```
Syntax: table=field,table=field
tx_news_domain_model_news=fal_media,tt_address=image
```

## Credits

This extension was created by Georg Ringer for [Studio Mitte, Linz](https://studiomitte.com) with ♥.

[Find more TYPO3 extensions we have developed](https://www.studiomitte.com/loesungen/typo3) that provide additional features for TYPO3 sites. 
