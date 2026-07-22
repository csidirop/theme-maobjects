# Changelog

All notable changes to this project will be documented in this file.

<!--
Version Strings to change before release:
 - /theme.ini
 - /package.json
 - /CHANGELOG.md

Placeholder for commit: [xxxxxxx](https://github.com/UB-Mannheim/theme-maobjects/commit/xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx))
 -->
## New
-

## v3.1
- Mainly styling updates ([a22c281](https://github.com/UB-Mannheim/theme-maobjects/commit/a22c281dac3d5351596b17e1612d0fd383213087), [fdc1cf0](https://github.com/UB-Mannheim/theme-maobjects/commit/fdc1cf012d1f14f815c00f0a099352a3342f9306), [775adae](https://github.com/UB-Mannheim/theme-maobjects/commit/775adae9c0773507a56240cf9e1ea8723a152201)).

## v3.0
The largest internal change is the Uni Mannheim asset migration, which removes reliance on externally hosted corporate-design resources and makes the theme more self-contained ([a558280](https://github.com/UB-Mannheim/theme-maobjects/commit/a558280d3c53d40b19fbff0620700552bb8627b3), [b98ab1d](https://github.com/UB-Mannheim/theme-maobjects/commit/b98ab1d608522cead6dc7c8634657ee940f53823), [cae8d89](https://github.com/UB-Mannheim/theme-maobjects/commit/cae8d89da15542f456da06089f6c57fc4e3c705a)).

- Added theme-specific styling for the Admin Tools maintenance mode page so maintenance screens inherit the MAObjects look and footer/header framing ([ab3469d](https://github.com/UB-Mannheim/theme-maobjects/commit/ab3469d2215e99b92ae581afa0041d3aa21c63bc), [30c4824](https://github.com/UB-Mannheim/theme-maobjects/commit/30c48249121fa1be67328e7f71e133e1bb2f62ef)).
- Added new theme options:
  - selectable item image sizes ([f4a69a2](https://github.com/UB-Mannheim/theme-maobjects/commit/f4a69a2700fcbf6b9b43786dbb28ce52f8501a18), [ec8e696](https://github.com/UB-Mannheim/theme-maobjects/commit/ec8e696942d024d3c8157c214e94afca96d0d006))
  - option to hide the exhibit heading and exhibit navigation ([a9a3f20](https://github.com/UB-Mannheim/theme-maobjects/commit/a9a3f2097ab5b6df00b3d5ee760580f85af12731)).
- Added dedicated lightGallery styling, initialization and configuration ([8db358f](https://github.com/UB-Mannheim/theme-maobjects/commit/8db358f61b268e38a77adc1bfe443baa05b9815a), [2f7510f](https://github.com/UB-Mannheim/theme-maobjects/commit/2f7510f054608ac6b5b08afe90a609ee1b87c1e7)).
- Added a search bar to the item tags page to make the tag cloud filterable by search query ([f76ec99](https://github.com/UB-Mannheim/theme-maobjects/commit/f76ec9951226a2e7886ca243fd044acdc8bc2177)).
- Added a configurable CI-style footer with theme settings for uploaded branding logos, footer column titles, footer link textareas, legal links, footer text, and per-platform social URLs. ([35e25be](https://github.com/UB-Mannheim/theme-maobjects/commit/35e25be0c9611d99e25d3e61c78ce694eec7db23))

#### Changed

- **Migrated external Uni Mannheim frontend dependencies into the theme bundle instead of loading remote assets ([a558280](https://github.com/UB-Mannheim/theme-maobjects/commit/a558280d3c53d40b19fbff0620700552bb8627b3), [b98ab1d](https://github.com/UB-Mannheim/theme-maobjects/commit/b98ab1d608522cead6dc7c8634657ee940f53823), [cae8d89](https://github.com/UB-Mannheim/theme-maobjects/commit/cae8d89da15542f456da06089f6c57fc4e3c705a)).**
- Restored the internal naming from the temporary `Mashare` rename back to `Center Row`-based identifiers where the theme code had not actually changed ownership ([b1132f3](https://github.com/UB-Mannheim/theme-maobjects/commit/b1132f3189a691f4fef7fa0da005bfe40b67156e)).
- Reworked footer link parsing so `html-input` textareas can be used for configurable footer blocks and legal links, including Omeka-aware resolution of internal paths like `"/imprint"` in subdirectory installs.
- Updated styling for:
  - Search Bar ([b04e311](https://github.com/UB-Mannheim/theme-maobjects/commit/fb04e311b8a429752eb946bfeca8a8905878bf6f))
  - Sort Links (in both items and collections browse pages) ([6148534](https://github.com/UB-Mannheim/theme-maobjects/commit/6148534112e72eb8a77e602e858763080edc4eb4)).
  - Output formats on item browse and show pages.
- Updated README and footer documentation to document the new configurable footer settings and textarea link format.

#### Fixed

- Fixed smaller item-page media issues, including display handling around file rendering and theme options for item media output ([ec8e696](https://github.com/UB-Mannheim/theme-maobjects/commit/ec8e696942d024d3c8157c214e94afca96d0d006)).
- Only render the Facets plugin block when it contains facet fields ([7a9e3d2](https://github.com/UB-Mannheim/theme-maobjects/commit/7a9e3d265ee60443a6ca5b02f7f3b194df5cd302)).


## [v2.1](https://github.com/UB-Mannheim/theme-maobjects/releases/tag/maobjects-v2.1)

This release focuses on better item & collection presentation, more consistent Uni Mannheim CI styling, improved media handling, and much more complete documentation, plus a few internal improvements.

#### Highlights
- Omeka 3.2 compatible ([6189447](https://github.com/UB-Mannheim/theme-maobjects/commit/6189447b4d908fd4e1b2ba94d4a31c5e1a907eff)).
- New layout and display options for items, collections and browse views and updated styling ([1642b86](https://github.com/UB-Mannheim/theme-maobjects/commit/1642b863393442b9537606b324b734b72f90bcc9), [bbcd345](https://github.com/UB-Mannheim/theme-maobjects/commit/bbcd345b8ccbd9cfe92a6ec64665e719b89c24f3), [d696911](https://github.com/UB-Mannheim/theme-maobjects/commit/d69691168ecd0ada93a57517c4d736cfb027ca52), [69db4d2](https://github.com/UB-Mannheim/theme-maobjects/commit/69db4d273cda2afd67b5492ddba7990538184b2f), [adbfb1a](https://github.com/UB-Mannheim/theme-maobjects/commit/adbfb1aa714b7d4eb2ea1ec77e70d95e9fbcdc2d)).
- Styling refined to better match University of Mannheim CI (lists, hover effects, navigation, buttons, facets) ([e86a093](https://github.com/UB-Mannheim/theme-maobjects/commit/e86a0930115d00b9bc283fcac0a1d8927df86abd), [32553b2](https://github.com/UB-Mannheim/theme-maobjects/commit/32553b29960429b36abc1d4971304d9df9d77435), [4cf4d98](https://github.com/UB-Mannheim/theme-maobjects/commit/4cf4d98b0ed3caed7d53937cd11da4f6d4a7c3f1), [6c70915](https://github.com/UB-Mannheim/theme-maobjects/commit/6c70915c9c0a6daf12d0f65e1c5337a0422fb4e8), [e27b879](https://github.com/UB-Mannheim/theme-maobjects/commit/e27b879570462aa2a42e8d4001a8cd162cf6fe37)).
- Extensive documentation and screenshots of _some_ theme options, including a proper `docs/` section ([dbbb654](https://github.com/UB-Mannheim/theme-maobjects/commit/dbbb654b3aeb8d0d6242905f0f5e9be9cff0202e), [e371c66](https://github.com/UB-Mannheim/theme-maobjects/commit/e371c660afe42aef0c69d076619469373cbdbe55)).
- More robust media support, including multi-media items and the files show page ([62080d1](https://github.com/UB-Mannheim/theme-maobjects/commit/62080d1548d344350761b634ae023f7bd925005c), [9ba8c0d](https://github.com/UB-Mannheim/theme-maobjects/commit/9ba8c0ddbad83bb4366033748855444d1143b1d6)).

#### And further new features
- [Use collapsible details elements to hide output formats in browse and show views](https://github.com/UB-Mannheim/theme-maobjects/commit/e2f5bd20cad4d17297e8a1eec4e9923692e16f8e)
- [Add an option to hide the secondary navigation in the browse item view](https://github.com/UB-Mannheim/theme-maobjects/commit/06efdb5c4ade66499fcda82dc1bcbbdef3f691e7)
- Add GH workflows ([daef07e](https://github.com/UB-Mannheim/theme-maobjects/commit/daef07e9c8ed211f61e86f821987623686e827e8), [cdc6dd1](https://github.com/UB-Mannheim/theme-maobjects/commit/cdc6dd174669df567571970f7557bf3432d2f1de))


## [v2.0.1](https://github.com/UB-Mannheim/theme-maobjects/releases/tag/v2.0.1)

v2.0.1 - Initial public release!
