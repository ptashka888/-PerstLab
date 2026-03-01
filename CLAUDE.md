# CLAUDE.md — PerstLab-Analyzer

## Project Overview

PerstLab-Analyzer is a Chrome extension (Manifest V3) that highlights user-specified words and phrases on web pages. It supports color-coded highlighting, word list import from `.txt` files, and provides statistics on word occurrences with location context (header, footer, body, links, lists, tables).

- **Author:** Ivan Leshchenko (Лещенко Иван)
- **License:** MIT
- **Language:** Russian UI, all user-facing strings are in Russian

## Repository Structure

```
/
├── CLAUDE.md                          # This file
├── LICENSE                            # MIT License
├── README.md                          # Project documentation (Russian)
└── PerstLab-Analyzer-pro-fool/        # Extension source directory
    ├── manifest.json                  # Chrome extension manifest (V3)
    ├── popup.html                     # Extension popup UI (HTML + inline CSS)
    ├── popup.js                       # Popup logic (tab switching, word management, statistics display)
    └── content.js                     # Content script (DOM highlighting, word counting, location detection)
```

## Architecture

### Chrome Extension Components

- **manifest.json** — Manifest V3 configuration. Declares permissions (`storage`, `activeTab`), registers `content.js` as a content script on all URLs, and sets `popup.html` as the browser action popup.
- **popup.html** — The extension popup with three tabs: "Основное" (main settings), "Статистика" (statistics), "Автор" (author info). All CSS is inline in a `<style>` block.
- **popup.js** — Manages the popup lifecycle:
  - Tab navigation (`.wh-tab` / `.wh-tab-content` class toggling)
  - Word list editing (textarea input + `.txt` file upload)
  - Color selection (yellow, pink, lightblue, lightgreen)
  - Sends `UPDATE_HIGHLIGHTS` messages to the content script via `chrome.tabs.sendMessage`
  - Requests and displays statistics via `REQUEST_STATS` messages
  - Polls statistics every 1 second via `setInterval`
  - Persists data with `chrome.storage.sync`
- **content.js** — Runs on every web page:
  - Receives highlight words and colors from popup via Chrome messaging
  - Traverses the DOM to find and wrap matching text nodes in `<span>` elements with per-word CSS classes
  - Tracks word occurrence counts (`wordStats`) and locations (`wordLocations`)
  - Uses `MutationObserver` with throttling (max 3 updates/sec, 300ms debounce) to re-highlight on dynamic DOM changes
  - Determines word location (header, footer, body, links, lists, tables) via `element.closest()` and heuristic position-based detection

### Message Protocol (popup ↔ content script)

| Message Type          | Direction         | Payload                            | Response                          |
|-----------------------|-------------------|------------------------------------|-----------------------------------|
| `UPDATE_HIGHLIGHTS`   | popup → content   | `{ words, colors }`               | `{ success, stats, locations }`   |
| `REQUEST_STATS`       | popup → content   | (none)                             | `{ stats, locations }`            |
| `STATS_UPDATE`        | content → popup   | `{ stats, locations }`             | (none)                            |

### Data Storage

Uses `chrome.storage.sync` with keys:
- `highlightWords` — `string[]` of words to highlight
- `wordColors` — `Record<string, string>` mapping each word to a CSS color

### CSS Class Naming Convention

- Highlight spans use class `highlight-{safeWord}` where `safeWord` replaces non-alphanumeric characters with `_` + hex charcode
- Tab system uses `wh-` prefix: `wh-tab`, `wh-tab-content`, `wh-highlight-word`, `wh-highlight-styles`, `wh-author-*`

## Development Workflow

### Loading the Extension Locally

1. Open `chrome://extensions/` in Chrome
2. Enable "Developer mode"
3. Click "Load unpacked" and select the `PerstLab-Analyzer-pro-fool/` directory

### No Build System

This project uses plain HTML/CSS/JavaScript with no build tools, bundlers, transpilers, or package managers. Edit the source files directly.

### No Test Framework

There are no automated tests. Manual testing is done by loading the extension in Chrome and verifying highlighting behavior on web pages.

## Key Conventions

- **No build step** — All files are vanilla JS/CSS/HTML, served directly by Chrome
- **No dependencies** — Only the Chrome Extensions API is used
- **Russian locale** — All UI text, comments, and console logs are in Russian
- **Inline styles** — `popup.html` contains all popup CSS in a `<style>` block; `content.js` dynamically injects highlight styles into the page's `<head>`
- **Commit messages** — Written in English, brief and descriptive

## Known Code Patterns

- `updateStatsDisplay()` is defined twice in `popup.js`: once inside the `DOMContentLoaded` closure (lines 108-198) and once globally (lines 245-338). The global version is used by `chrome.runtime.onMessage` listener
- Word processing sorts words by length (longest first) to avoid partial-match conflicts in regex replacement
- `escapeRegExp()` is used to safely build regex from user input
- `isCodeOrPreElement()` prevents highlighting inside `<code>`, `<pre>`, `<script>`, `<style>` tags
- Throttling in `content.js` uses a combination of counter-based rate limiting and `setTimeout` debouncing

## Important Notes for AI Assistants

- The extension source lives in `PerstLab-Analyzer-pro-fool/`, not the repo root
- There is no `package.json`, `node_modules`, or any Node.js tooling
- There is no background/service worker — only a popup and a content script
- `manifest.json` references `styles.css` in `web_accessible_resources` but this file does not exist in the repo; styles are injected dynamically by `content.js`
- When modifying the extension, test by reloading it in `chrome://extensions/` and refreshing target pages
