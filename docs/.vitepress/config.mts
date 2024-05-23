import {defineConfig} from 'vitepress'

export default defineConfig({
    title: "SQL Server Lite",
    description: "A simple, lightweight, and efficient connection.",
    lang: 'en-US',
    lastUpdated: false,
    base: '/SQLServerLite',
    themeConfig: {
        footer: {
            message: 'Released under the MIT License.',
            copyright: 'Copyright © 2021-2024 Raul Mauricio Uñate'
        },
        editLink: {
            pattern: 'https://github.com/rmunate/SQLServerLite/tree/main/docs/:path'
        },
        logo: '/img/favicon.svg',
        nav: [
            {text: 'v2.1.0', link: '/'},
        ],
        sidebar: [
            {
                text: 'Getting Started',
                collapsed: false,
                items: [
                    {text: 'Introduction', link: '/getting-started/introduction'},
                    {text: 'Installation', link: '/getting-started/installation'},
                    {text: 'Drivers Windows', link: '/getting-started/windows'},
                    {text: 'Drivers Linux', link: '/getting-started/linux'},
                    {text: 'Drivers IOS', link: '/getting-started/mac'},
                    {text: 'Release Notes', link: '/getting-started/changelog'},
                ]
            }, {
                text: 'Usage',
                collapsed: false,
                items: [
                    {text: 'Connection', link: '/usage/connection'},
                    {text: 'Attributes', link: '/usage/attributes'},
                    {text: 'Transaction Control', link: '/usage/transaction-control'},
                    {text: 'Insert', link: '/usage/insert'},
                    {text: 'Update', link: '/usage/update'},
                    {text: 'Delete', link: '/usage/delete'},
                    {text: 'Select', link: '/usage/select'},
                    {text: 'Stored Procedures', link: '/usage/stored-procedures'},
                    {text: 'Final Methods', link: '/usage/final-methods'},
                ]
            }, {
                text: 'Contribute',
                collapsed: false,
                items: [
                    {text: 'Bug Report', link: 'contribute/report-bugs'},
                    {text: 'Contribution', link: 'contribute/contribution'}
                ]
            }
        ],

        socialLinks: [
            {icon: 'github', link: 'https://github.com/rmunate/SQLServerLite'}
        ],
        search: {
            provider: 'local'
        }
    },
    head: [
        ['link', { 
                rel: 'stylesheet', 
                href: '/SQLServerLite/css/style.css' 
            }
        ],
        ['link', {
                rel: 'icon',
                href: '/SQLServerLite/img/logo.svg',
                type: 'image/png'
            }
        ],
        ['meta', {
                property: 'og:image',
                content: '/SQLServerLite/img/logo-github.png' 
            }
        ],
        ['meta', {
                property: 'og:image:secure_url',
                content: '/SQLServerLite/img/logo-github.png'
            }
        ],
        ['meta', {
                property: 'og:image:width',
                content: '600'
            }
        ],
        ['meta', {
                property: 'og:image:height',
                content: '400'
            }
        ],
        ['meta', {
                property: 'og:title',
                content: 'SQLServerLite'
            }
        ],
        ['meta', {
                property: 'og:description',
                content: 'A simple, lightweight, and efficient connection.! 🚀'
            }
        ],
        ['meta', {
                property: 'og:url',
                content: 'https://rmunate.github.io/SQLServerLite/'
            }
        ],
        ['meta', {
                property: 'og:type',
                content: 'website'
            }
        ],
    ],
})
