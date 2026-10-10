---
name: APEXIK
description: Race-day stories engineered into collectible objects through a precise automotive editorial system.
colors:
  ink: "#101010"
  muted-ink: "#68665f"
  warm-paper: "#f2efe8"
  white: "#ffffff"
  signal-red: "#e21b2d"
  night-navy: "#10243e"
  heritage-gold: "#b89454"
  hairline: "rgba(16, 16, 16, 0.18)"
typography:
  display:
    fontFamily: "Barlow Condensed, Arial Narrow, sans-serif"
    fontSize: "clamp(4.8rem, 11vw, 10.8rem)"
    fontWeight: 700
    lineHeight: 0.75
    letterSpacing: "-0.03em"
  headline:
    fontFamily: "Barlow Condensed, Arial Narrow, sans-serif"
    fontSize: "clamp(3rem, 5.5vw, 6rem)"
    fontWeight: 700
    lineHeight: 0.86
    letterSpacing: "-0.025em"
  title:
    fontFamily: "Barlow Condensed, Arial Narrow, sans-serif"
    fontSize: "2.4rem"
    fontWeight: 600
    lineHeight: 0.92
  body:
    fontFamily: "Manrope, Arial, sans-serif"
    fontSize: "1rem"
    fontWeight: 400
    lineHeight: 1.6
  label:
    fontFamily: "Manrope, Arial, sans-serif"
    fontSize: "0.72rem"
    fontWeight: 700
    lineHeight: 1.2
    letterSpacing: "0.08em"
rounded:
  square: "0"
  circle: "50%"
spacing:
  "2xs": "0.5rem"
  xs: "0.75rem"
  sm: "1rem"
  md: "1.5rem"
  lg: "2.5rem"
  xl: "4rem"
  "2xl": "7rem"
components:
  button-primary:
    backgroundColor: "{colors.ink}"
    textColor: "{colors.white}"
    typography: "{typography.label}"
    rounded: "{rounded.square}"
    padding: "0 20px"
    height: "48px"
  button-light:
    backgroundColor: "{colors.white}"
    textColor: "{colors.ink}"
    typography: "{typography.label}"
    rounded: "{rounded.square}"
    padding: "0 20px"
    height: "48px"
  tab-active:
    backgroundColor: "transparent"
    textColor: "{colors.ink}"
    typography: "{typography.label}"
    rounded: "{rounded.square}"
    padding: "0 30px 15px"
  field-newsletter:
    backgroundColor: "transparent"
    textColor: "{colors.white}"
    typography: "{typography.body}"
    rounded: "{rounded.square}"
    padding: "13px 0"
---

# Design System: APEXIK

## Overview

**Creative North Star: "The Pit-Wall Editorial"**

APEXIK turns race-day tension into a premium editorial commerce world: warm paper grounds the experience, ink-black type gives it authority, and signal red behaves like a race-control cue. The composition feels measured but urgent, pairing exact hairlines and disciplined grids with oversized condensed headlines, hard crops, italic acceleration, lap numbers, and geometric track signals.

The system is precise, tense, premium, and tactile rather than glossy. It treats every product as the physical continuation of a story, never as an isolated logo item. Its explicit anti-reference is the generic logo-merch grid: repetitive tiles, neutral marketplace styling, and decoration without narrative hierarchy do not belong.

**Key Characteristics:**
- Warm-paper editorial canvas with ink-black structural contrast.
- Signal red reserved for urgency, state, and decisive action.
- Condensed uppercase display type at intentionally dramatic scale.
- Square controls, fine rules, hard crops, and asymmetric editorial pacing.
- Race telemetry cues expressed through numerals, rails, circles, and diagonal geometry.
- Story context remains visually connected to every product decision.

## Colors

The palette combines warm print-stock neutrals with race-control contrast and restrained heritage accents.

### Primary
- **Signal Red** (`signal-red`): Marks selection, urgency, key commerce actions, focus, and rare high-energy surfaces.

### Secondary
- **Night Navy** (`night-navy`): Provides a cool, technical field for radio and strategy stories without competing with the primary signal.

### Tertiary
- **Heritage Gold** (`heritage-gold`): Adds measured provenance and premium warmth to metadata, proof points, and secondary story cues.

### Neutral
- **Ink Black** (`ink`): The dominant text, navigation, footer, button, and high-contrast surface color.
- **Muted Ink** (`muted-ink`): Supports descriptions, metadata, and de-emphasized copy.
- **Warm Paper** (`warm-paper`): The default page canvas and the material anchor of the system.
- **Editorial White** (`white`): Used for navigation, commerce surfaces, reversed text, and moments of clean separation.
- **Hairline Ink** (`hairline`): Divides regions, rows, tabs, and controls without adding card chrome.

### Named Rules

**The Signal-Is-Sparse Rule.** Use signal red as a race-control event, not as ambient decoration; large red fields are reserved for intentional campaign or subscription moments.

**The Warm-Stock Rule.** Default to warm paper rather than cold gray or pure white for the primary reading canvas.

## Typography

**Display Font:** Barlow Condensed (with Arial Narrow and sans-serif fallbacks)  
**Body Font:** Manrope (with Arial and sans-serif fallbacks)

**Character:** Barlow Condensed supplies compressed race-poster force and editorial pace; Manrope supplies calm, contemporary legibility. The contrast is deliberate: headlines perform, body copy explains.

### Hierarchy
- **Display** (700, fluid oversized scale, 0.75 line-height): Hero statements and campaign titles; uppercase, tightly tracked, and selectively italicized for motion.
- **Headline** (700, fluid section scale, 0.86 line-height): Section openings and major story propositions, normally capped around 12 characters per line.
- **Title** (600, compact editorial scale, 0.92 line-height): Story rows, article titles, drawer headings, and other mid-level narrative labels.
- **Body** (400, 1rem, 1.6 line-height): Explanatory and commerce copy; keep primary reading measures near 42–55 characters.
- **Label** (700, 0.72rem, 0.08em tracking, uppercase): Navigation, metadata, tabs, badges, buttons, and race-control annotations.

### Named Rules

**The Two-Speed Rule.** Display type carries the drama; body type carries the facts. Do not use condensed display faces for long reading passages.

**The Compressed-Headline Rule.** Major headlines stay uppercase, tightly tracked, and short enough to preserve their poster-like silhouette.

## Layout

The site uses a centered fluid shell capped at 1480px. Desktop gutters are 24px per side, tightening to 18px below 1040px and 14px below 720px. Editorial sections use generous vertical spacing, generally flowing from the established spacing scale and expanding responsively with `clamp()`.

Desktop commerce uses four columns with narrow 18px gutters and a larger 42px row rhythm. It collapses to two columns below 1040px and one below 720px. Story modules deliberately vary the grid: split-screen features, indexed rows, and asymmetric journal columns create publication pacing instead of a repeated card wall.

At mobile sizes, stacked content preserves hierarchy rather than merely shrinking it. Buttons become full-width, dense navigation moves to an off-canvas panel, split stories become sequential panels, and decorative controls that do not help the core task recede.

**The Editorial-Rhythm Rule.** Alternate expansive story fields with tightly ruled product or index sections; never make the entire page one undifferentiated tile grid.

## Elevation & Depth

The system is flat by default. Depth comes from tonal blocks, full-bleed color, overlap, sticky positioning, hard crops, fine borders, and large geometric layers—not from floating cards. Strong shadows appear only on temporary off-canvas surfaces: the mobile menu and cart drawer use broad lateral shadows to clarify that they sit above the page. The sticky header uses translucency and blur as a functional layer cue.

### Shadow Vocabulary
- **Drawer Left Lift** (`24px 0 60px rgba(0,0,0,.22)`): Applied only to the mobile menu entering from the left.
- **Drawer Right Lift** (`-24px 0 60px rgba(0,0,0,.24)`): Applied only to the cart drawer entering from the right.

### Named Rules

**The Flat-Until-Overlay Rule.** Resting content surfaces remain shadowless; elevation is reserved for temporary navigation and commerce layers.

## Shapes

The dominant form language is square and mechanical. Buttons, tabs, badges, selectors, product actions, and panels have no corner rounding. One-pixel borders and hard clipping create the feeling of printed plates and technical instruments. Circles are meaningful exceptions used for signal lamps, track geometry, counters, and indexed race rails—not as generic softening.

**The Purposeful-Circle Rule.** Use circles only when they communicate signal, count, motion, or track geometry; never round every container by default.

## Components

### Buttons

Buttons feel compact, decisive, and engineered.

- **Shape:** Square edges with a one-pixel border and a 48px minimum height.
- **Primary:** Ink background with white uppercase label text and 20px horizontal padding.
- **Hover / Focus:** Hover lifts by 2px with the system easing; keyboard focus uses a 3px signal-red outline offset by 4px.
- **Light:** White background and border with ink text for dark or chromatic hero fields.

### Chips

- **Style:** Filter links and tabs remain square, compact, and uppercase. Unselected items use transparent backgrounds and hairline borders or underlines.
- **State:** Selected filters invert to ink with white text; selected tabs keep a transparent field and gain a 3px signal-red baseline.

### Cards / Containers

Product cards are image-led editorial units, not boxed marketplace cards.

- **Corner Style:** Square and hard-clipped.
- **Background:** The product visual supplies a matte color field; product metadata sits directly on the page canvas.
- **Shadow Strategy:** No resting shadow.
- **Border:** None around the card; adjacent editorial lists use shared hairline rules instead.
- **Internal Padding:** Product art fields use 20px; metadata begins 16px below the visual.
- **Behavior:** Product art subtly contracts on hover, preserving a precise, tactile response without floating above the page.

### Inputs / Fields

- **Style:** Inputs are square and minimal. Newsletter fields are transparent with a two-pixel white baseline; commerce selects and quantity fields use a one-pixel hairline border on a white or transparent field.
- **Focus:** Global focus visibility is a 3px signal-red outline with a 4px offset; newsletter fields retain their underline treatment.
- **Error / Disabled:** Preserve the same geometry and expose state through concise status text and accessible semantics rather than decorative containers.

### Navigation

The sticky desktop header is a translucent white strip with an 82px height, hairline lower border, centered uppercase labels, and red hover state. The shop mega-menu opens as a full-width editorial panel divided by rules, with a featured story block rather than a generic category dropdown. Below 1040px, navigation becomes a left drawer whose large condensed links read like an indexed contents page.

### Story Index Row

Story index rows pair a graphic number plate, condensed uppercase title, restrained summary, and square arrow action. Horizontal rules carry the structure. On mobile, the visual spans the full row and the copy/action pair remains compact beneath it.

### Race Story Hero

The hero is a full-width dark or chromatic editorial panel using oversized italic condensed type, an oversized translucent index number, diagonal geometry, and compact telemetry-style controls. Motion is limited to deliberate fades, transforms, and the system easing, with a complete reduced-motion override.

## Do's and Don'ts

### Do:
- **Do** use warm paper, ink, and hairline rules as the default editorial foundation.
- **Do** reserve signal red for state, urgency, focus, and decisive actions.
- **Do** combine short uppercase display statements with calm, readable body copy.
- **Do** use lap numbers, indexed rails, diagonals, and circles when they reinforce a specific race story.
- **Do** preserve the 1480px shell, responsive column collapse, and full-width mobile actions.
- **Do** provide visible keyboard focus and respect reduced-motion preferences.

### Don't:
- **Don't** reduce the catalog to a generic logo-merch grid with repeated boxed cards and no story context.
- **Don't** add rounded cards, pill buttons, soft gradients, or ambient drop shadows to resting content.
- **Don't** use signal red across most of a screen unless the entire section is an intentional campaign-level event.
- **Don't** use condensed display type for paragraphs, form entry, or dense transactional information.
- **Don't** center every section or regularize every grid; asymmetric editorial pacing is part of the identity.
- **Don't** add decorative racing motifs unless they communicate hierarchy, timing, signal, or narrative provenance.
