# Documentation

This package uses VuePress to build comprehensive documentation.

**Production URL:** [https://livewire-select.thejano.com/](https://livewire-select.thejano.com/)

## Quick Start

### Install Dependencies

```bash
npm install
```

### Development Server

Run the local development server:

```bash
npm run docs:dev
```

Documentation will be available at: `http://localhost:8080/`

### Build for Production

Build static documentation site:

```bash
npm run docs:build
```

This generates the static site in the `docs/` folder.

## Documentation Structure

```
docs-src/
├── .vuepress/
│   ├── config.js       # VuePress configuration
│   └── public/         # Static assets (copied to docs/ root)
│       └── CNAME       # Custom domain (preserved in build)
├── README.md           # Homepage
└── guide/              # Documentation pages
    ├── README.md           # Introduction
    ├── installation.md     # Installation guide
    ├── quickstart.md       # Quick start
    ├── features.md         # Features overview
    ├── async-loading.md    # Async loading
    ├── multiple-selection.md
    ├── custom-slots.md     # Custom slots
    ├── themes.md           # Theme configuration
    ├── api.md              # API reference
    ├── examples.md         # Examples
    ├── customization.md    # Customization
    └── troubleshooting.md  # Troubleshooting
```

**Note:** Files in `docs-src/.vuepress/public/` are copied to the root of `docs/` during build.

## Adding New Pages

1. Create a new `.md` file in `docs-src/guide/`
2. Add it to the sidebar in `docs-src/.vuepress/config.js`:

```javascript
sidebar: {
  '/guide/': [
    {
      text: 'Your Section',
      children: [
        '/guide/your-new-page'
      ]
    }
  ]
}
```

## Custom Domain

This project uses a custom domain: **livewire-select.thejano.com**

The CNAME file is configured in `docs-src/.vuepress/public/CNAME` and automatically copied to `docs/CNAME` during build.

### To change the domain:

1. Edit `docs-src/.vuepress/public/CNAME` with your domain
2. Configure DNS:
   - Type: `CNAME`
   - Name: `livewire-select` (or your subdomain)
   - Value: `drpshtiwan.github.io`
3. Rebuild and deploy

## Deployment

The documentation can be deployed to:

### GitHub Pages

1. Build the documentation:
   ```bash
   npm run docs:build
   ```

2. Commit the `docs/` folder:
   ```bash
   git add docs/
   git commit -m "Build documentation"
   git push
   ```

3. Configure GitHub Pages:
   - Go to repository Settings → Pages
   - Source: Deploy from a branch
   - Branch: `main`
   - Folder: `/docs`
   - Custom domain: `livewire-select.thejano.com` (already configured)
   - Enforce HTTPS: ✅ Enabled

**Note:** The `docs/CNAME` file is automatically generated from `docs-src/.vuepress/public/CNAME` during build. The custom domain `livewire-select.thejano.com` is already configured and will be preserved.

### Other Platforms

The built documentation in `docs/` folder can be deployed to:
- Netlify
- Vercel
- Cloudflare Pages
- Any static hosting service

## Requirements

- Node.js 18.19.0+
- npm or yarn

## Troubleshooting

### Port Already in Use

```bash
npm run docs:dev -- --port 8081
```

### Clear Cache

```bash
rm -rf node_modules package-lock.json
npm install
```

## Learn More

- [VuePress Documentation](https://v2.vuepress.vuejs.org/)
- [Markdown Guide](https://www.markdownguide.org/)

