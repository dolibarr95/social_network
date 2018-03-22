# social_network
Create a social map.

## Install
1. Transfer this module in your custom folder
2. Create an extrafield in Third parties
3. Activate the module

## Extrafield
Complementary attributes (thirdparty)

| Attribute         | Value  |
| -------------     | -----: |
| Label    |As you want|
| Attribute code    |salon|
| Type | Checkboxes from table|
| Value | `salon:label:rowid::1=1 order by label` |
| Position | 0 |
| Default value (Database) ||
| Unique ||
| Can always be edited | Yes |
| Hidden ||
| Show by default on list view ||

## Operating mode
Open the new link 'Salon' in Third party
Create/Edit a label (works as tags) 
_eg: 
Salon de l'automobile 2017
CES las vegas 2018
..._
/!\ don't create label with same name /!\ 


## Warning
First test in non production Dolibarr.
