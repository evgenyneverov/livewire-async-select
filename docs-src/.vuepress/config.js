import { defaultTheme } from '@vuepress/theme-default'
import { defineUserConfig } from 'vuepress/cli'
import { viteBundler } from '@vuepress/bundler-vite'
import { searchPlugin } from '@vuepress/plugin-search'

export default defineUserConfig({
  title: 'Livewire Async Select',
  description: 'A powerful async select component for Laravel Livewire with Alpine.js - a modern alternative to Select2',
  
  // Base URL - using '/' for custom domain (livewire-select.thejano.com)
  // If deploying to GitHub Pages without custom domain, change to: '/livewire-async-select/'
  base: '/',
  
  // Output directory for built files (used by GitHub Pages)
  dest: 'docs',
  
  head: [
    ['link', { rel: 'icon', href: '/favicon.ico' }],
    ['meta', { name: 'theme-color', content: '#3eaf7c' }],
    ['meta', { name: 'apple-mobile-web-app-capable', content: 'yes' }],
    ['meta', { name: 'apple-mobile-web-app-status-bar-style', content: 'black' }]
  ],

  bundler: viteBundler(),

  plugins: [
    searchPlugin({
      locales: {
        '/': {
          placeholder: 'Search',
        },
      },
      maxSuggestions: 10,
      hotKeys: ['s', '/'],
    }),
  ],

  theme: defaultTheme({
    repo: 'drpshtiwan/livewire-async-select',
    editLink: false,
    docsDir: 'docs-src',
    lastUpdated: true,
    
    navbar: [
      { text: 'Guide', link: '/guide/' },
      { text: 'Features', link: '/guide/features' },
      { text: 'API Reference', link: '/guide/api' },
      { text: 'Examples', link: '/guide/examples' }
    ],
    
    sidebar: {
      '/guide/': [
        {
          text: 'Getting Started',
          collapsible: false,
          children: [
            '/guide/',
            '/guide/installation',
            '/guide/quickstart'
          ]
        },
        {
          text: 'Features',
          collapsible: false,
          children: [
            '/guide/features',
            '/guide/async-loading',
            '/guide/multiple-selection',
            '/guide/custom-slots',
            '/guide/themes',
            '/guide/authentication'
          ]
        },
        {
          text: 'Advanced',
          collapsible: false,
          children: [
            '/guide/api',
            '/guide/examples',
            '/guide/customization',
            '/guide/default-values',
            '/guide/validation',
            '/guide/select2-comparison',
            '/guide/troubleshooting'
          ]
        }
      ]
    }
  }),

  markdown: {
    code: {
      lineNumbers: false
    }
  }
})

