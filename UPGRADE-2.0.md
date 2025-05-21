# UPGRADE FROM `1.5.X` TO `2.0.0`

- Use the twig hooks, in particular the `sylius_admin.menu_item.update.content.sections.form`, to modify the menu item form
- The twig function `menu_first_level` is deprecated, use the twig component `monsieur_biz:shared:menu` instead
