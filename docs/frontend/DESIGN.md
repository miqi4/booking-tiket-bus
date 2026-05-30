---
name: Reliable Transit
colors:
  surface: '#fcf9f3'
  surface-dim: '#dcdad4'
  surface-bright: '#fcf9f3'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#f6f3ee'
  surface-container: '#f1ede8'
  surface-container-high: '#ebe8e2'
  surface-container-highest: '#e5e2dd'
  on-surface: '#1c1c19'
  on-surface-variant: '#424751'
  inverse-surface: '#31302d'
  inverse-on-surface: '#f3f0eb'
  outline: '#727782'
  outline-variant: '#c2c6d2'
  surface-tint: '#1960a6'
  primary: '#004782'
  on-primary: '#ffffff'
  primary-container: '#185fa5'
  on-primary-container: '#c1d9ff'
  inverse-primary: '#a4c9ff'
  secondary: '#3e6922'
  on-secondary: '#ffffff'
  secondary-container: '#bbee97'
  on-secondary-container: '#426d26'
  tertiary: '#683c0a'
  on-tertiary: '#ffffff'
  tertiary-container: '#845321'
  on-tertiary-container: '#ffcea5'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#d4e3ff'
  primary-fixed-dim: '#a4c9ff'
  on-primary-fixed: '#001c39'
  on-primary-fixed-variant: '#004883'
  secondary-fixed: '#bef19a'
  secondary-fixed-dim: '#a3d480'
  on-secondary-fixed: '#0a2100'
  on-secondary-fixed-variant: '#27500a'
  tertiary-fixed: '#ffdcc0'
  tertiary-fixed-dim: '#fab97e'
  on-tertiary-fixed: '#2d1600'
  on-tertiary-fixed-variant: '#683c0a'
  background: '#fcf9f3'
  on-background: '#1c1c19'
  surface-variant: '#e5e2dd'
typography:
  h1:
    fontFamily: Work Sans
    fontSize: 32px
    fontWeight: '700'
    lineHeight: '1.2'
  h1-mobile:
    fontFamily: Work Sans
    fontSize: 26px
    fontWeight: '700'
    lineHeight: '1.2'
  h2:
    fontFamily: Work Sans
    fontSize: 24px
    fontWeight: '600'
    lineHeight: '1.3'
  h3:
    fontFamily: Work Sans
    fontSize: 20px
    fontWeight: '600'
    lineHeight: '1.4'
  body:
    fontFamily: Work Sans
    fontSize: 16px
    fontWeight: '400'
    lineHeight: '1.5'
  label-form:
    fontFamily: Work Sans
    fontSize: 14px
    fontWeight: '500'
    lineHeight: '1.2'
  caption:
    fontFamily: Work Sans
    fontSize: 13px
    fontWeight: '400'
    lineHeight: '1.4'
rounded:
  sm: 0.125rem
  DEFAULT: 0.25rem
  md: 0.375rem
  lg: 0.5rem
  xl: 0.75rem
  full: 9999px
spacing:
  base: 8px
  xs: 4px
  sm: 12px
  md: 24px
  lg: 48px
  xl: 64px
  gutter: 20px
  container-max: 1200px
---

## Brand & Style
This design system centers on reliability, speed, and logistical precision for the Indonesian transport sector. The brand personality is professional and authoritative, yet accessible, aiming to reduce the anxiety associated with travel booking. 

The aesthetic follows a **Modern Corporate** style—utilizing high-quality typography and ample whitespace to ensure clarity in complex data environments like seat selection and scheduling. It balances a utilitarian "transit" feel with modern digital refinements to build high user trust.

## Colors
The palette is rooted in a deep "Transit Blue" that signals stability and established service. A system of "Light" tint backgrounds is used for semantic messaging, ensuring that status updates (Confirmed, Pending, Cancelled) are legible without overwhelming the interface. Neutral tones are kept cool to maintain a clean, professional environment, while the light grey is reserved for structural rhythm like alternating rows in schedules.

## Typography
**Work Sans** is selected for its exceptional legibility in technical and data-heavy layouts. Its grounded, professional character suits the transport industry perfectly. 

- **Hierarchy:** Use H1 for main page titles (e.g., "Select Your Seat"). H2 and H3 should be used for card titles and section headers.
- **Form Inputs:** Labels use a medium weight at 14px to remain distinct from the user's input text.
- **Data Densities:** For ticket details and time-stamps, use the "Caption" style to maintain a clear visual hierarchy in compact spaces.

## Layout & Spacing
The system utilizes a **12-column fluid grid** for desktop and a **4-column grid** for mobile. 

- **Grid:** On desktop, use a 1200px max-width container centered with 24px side margins. 
- **Rhythm:** Spacing follows an 8px base unit. Use `md` (24px) for padding within cards and `lg` (48px) for vertical section separation.
- **Mobile Reflow:** In mobile views, ticket cards should stack vertically, and the search filter should transform into a sticky bottom sheet or a full-screen modal to prioritize the booking flow.

## Elevation & Depth
Depth is used sparingly to maintain a "clean" and "flat-plus" professional look. 

- **Surface Layers:** Use a subtle 1px border (#D1D5DB) for secondary cards and containers.
- **Shadows:** Apply a soft, low-opacity shadow (0px 4px 12px rgba(0,0,0,0.05)) only to the primary booking card and floating navigation bars to signify "active" priority.
- **Tonal Depth:** Use the `Neutral Light` color (#F2F3F4) to define the background of the page, allowing white cards to pop forward.

## Shapes
A **Soft** shape language (4px - 8px radius) is used to balance modern friendliness with a structured, reliable feel. 

- **Primary Radius:** 4px (0.25rem) for buttons and input fields to maintain a crisp, efficient look.
- **Container Radius:** 8px (0.5rem) for ticket cards and modals to soften the overall layout.
- **Interactive Elements:** Use the 4px radius for seat icons in the interactive map to ensure they look like tangible, clickable units.

## Components

### Buttons
- **Primary:** Solid `#185FA5` with white text. High emphasis. 
- **Secondary:** Outlined `#185FA5` with 1px border. Used for "Modify Search" or "Back."
- **Success:** Solid `#27500A` for "Confirm Payment" or "Ticket Issued."

### Interactive Seat Map
- **Available:** White background with `#185FA5` 1px border.
- **Selected:** Solid `#185FA5` with a checkmark icon.
- **Occupied:** Solid `#F2F3F4` with no border; disabled interaction.
- **Legend:** Always display a caption-sized legend below the map.

### Cards
- **Ticket Card:** White background, 8px radius, 1px border. Use a dashed vertical line to separate the "Time/Route" section from the "Price/Book" action area to mimic a physical ticket.

### Status Badges
- **Confirmed:** Success Light background with Success Color text.
- **Pending:** Warning Light background with Warning Color text.
- **Cancelled:** Danger Light background with Danger Color text.

### Step Indicators
Horizontal layout at the top of the booking flow. Use the Primary color for completed and active steps, and Neutral Light for upcoming steps. Use a connector line between steps.

### Search Form
Horizontal bar for desktop, vertical stack for mobile. Use clear icons for "Origin," "Destination," and "Date" to minimize cognitive load.