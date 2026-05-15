<?php

namespace Database\Seeders;

use App\Enums\ProjectType;
use App\Enums\SkillCategory;
use App\Models\Experience;
use App\Models\Project;
use App\Models\Setting;
use App\Models\Skill;
use App\Support\PortfolioSettings;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PortfolioSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedSettings();
        $this->seedSkills();
        $this->seedExperiences();
        $this->seedProjects();
    }

    private function seedSettings(): void
    {
        $defaults = [
            PortfolioSettings::PRIVATE_PASSWORD => Hash::make('123456'),
            PortfolioSettings::ACCENT => 'indigo',
            PortfolioSettings::DEFAULT_THEME => 'dark',

            PortfolioSettings::HERO_NAME => 'Mekan Agamyradov',
            PortfolioSettings::HERO_ROLE => json_encode([
                'ru' => 'Full-Stack разработчик',
            ], JSON_UNESCAPED_UNICODE),
            PortfolioSettings::HERO_BIO => json_encode([
                'ru' => 'Разрабатываю веб-приложения полного цикла — от отзывчивых интерфейсов до надёжной серверной архитектуры. 4+ лет опыта в создании продуктов, которыми приятно пользоваться.',
            ], JSON_UNESCAPED_UNICODE),

            PortfolioSettings::CONTACT_EMAIL => 'mekan.developer@gmail.com',
            PortfolioSettings::CONTACT_PHONE => '+99362724494',
            PortfolioSettings::CONTACT_LOCATION => json_encode([
                'ru' => 'Ашхабад, Туркменистан',
            ], JSON_UNESCAPED_UNICODE),

            PortfolioSettings::SOCIAL_GITHUB => 'https://github.com/Mekan-developer',
            PortfolioSettings::SOCIAL_LINKEDIN => 'https://www.linkedin.com/in/mekan-developer',
            PortfolioSettings::SOCIAL_TWITTER => null,
        ];

        foreach ($defaults as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }
    }

    private function seedSkills(): void
    {
        if (Skill::query()->exists()) {
            return;
        }

        $skills = [
            // Frontend
            ['name' => 'Vue.js',        'abbr' => 'Vu', 'category' => SkillCategory::Frontend, 'percent' => 82],
            ['name' => 'Tailwind CSS',  'abbr' => 'Tw', 'category' => SkillCategory::Frontend, 'percent' => 90],
            ['name' => 'Alpine.js',     'abbr' => 'Al', 'category' => SkillCategory::Frontend, 'percent' => 78],
            ['name' => 'Inertia.js',    'abbr' => 'In', 'category' => SkillCategory::Frontend, 'percent' => 80],
            ['name' => 'HTML / CSS',    'abbr' => 'HC', 'category' => SkillCategory::Frontend, 'percent' => 92],

            // Backend
            ['name' => 'PHP',           'abbr' => 'Ph', 'category' => SkillCategory::Backend, 'percent' => 93],
            ['name' => 'Laravel',       'abbr' => 'Lv', 'category' => SkillCategory::Backend, 'percent' => 95],
            ['name' => 'MySQL',         'abbr' => 'My', 'category' => SkillCategory::Backend, 'percent' => 88],
            ['name' => 'PostgreSQL',    'abbr' => 'PG', 'category' => SkillCategory::Backend, 'percent' => 80],
            ['name' => 'Redis',         'abbr' => 'Rd', 'category' => SkillCategory::Backend, 'percent' => 74],
            ['name' => 'REST API',      'abbr' => 'RA', 'category' => SkillCategory::Backend, 'percent' => 90],

            // Tools
            ['name' => 'Git / GitHub',  'abbr' => 'Gt', 'category' => SkillCategory::Tools, 'percent' => 92],
            ['name' => 'Docker',        'abbr' => 'Dk', 'category' => SkillCategory::Tools, 'percent' => 75],
            ['name' => 'Linux',         'abbr' => 'Lx', 'category' => SkillCategory::Tools, 'percent' => 80],
            ['name' => 'Figma',         'abbr' => 'Fg', 'category' => SkillCategory::Tools, 'percent' => 72],
            ['name' => 'Composer',      'abbr' => 'Co', 'category' => SkillCategory::Tools, 'percent' => 88],
        ];

        foreach ($skills as $i => $skill) {
            Skill::create([
                'name' => $skill['name'],
                'abbr' => $skill['abbr'],
                'category' => $skill['category']->value,
                'percent' => $skill['percent'],
                'sort_order' => $i,
            ]);
        }
    }

    private function seedExperiences(): void
    {
        if (Experience::query()->exists()) {
            return;
        }

        $experiences = [
            [
                'company' => 'Lebizli Tehnologiya Merkezi',
                'duration' => 'Aug 2025 — Present',
                'role' => [
                    'en' => 'Full-Stack Developer',
                    'ru' => 'Full-Stack разработчик',
                    'tk' => 'Full-Stack döredijisi',
                ],
                'description' => [
                    'en' => 'Developing and maintaining modern web applications using Laravel and Vue.js. Contributing to backend and frontend architecture improvements, working with AdonisJS. Focused on scalable architecture, API development, and performance optimization.',
                    'ru' => 'Разрабатываю и поддерживаю современные веб-приложения на Laravel и Vue.js. Участвую в улучшении архитектуры бэкенда и фронтенда, работаю с AdonisJS. Акцент на масштабируемой архитектуре, разработке API и оптимизации производительности.',
                    'tk' => 'Laravel we Vue.js ulanyp döwrebap web goşundylaryny işläp düzýärin we goldaýaryn. AdonisJS bilen işläp, serwer we isleg tarapy arhitekturasyny kämilleşdirýärin. Giňeldilip bilinýän arhitektura, API işläp düzmek we öndürijiligi optimizasiýa üns berýärin.',
                ],
            ],
            [
                'company' => 'Arassa Nusga',
                'duration' => 'Jun 2024 — Nov 2024',
                'role' => [
                    'en' => 'Full-Stack Developer',
                    'ru' => 'Full-Stack разработчик',
                    'tk' => 'Full-Stack döredijisi',
                ],
                'description' => [
                    'en' => 'Developed full-stack applications using Laravel and Vue.js. Built a Duolingo-like language learning platform with a custom CRM system. Worked with Yii2 framework and focused on improving performance and user experience of existing projects.',
                    'ru' => 'Разрабатывал full-stack приложения на Laravel и Vue.js. Создал платформу для изучения языков по типу Duolingo с собственной CRM-системой. Работал с Yii2, улучшал производительность и пользовательский опыт существующих проектов.',
                    'tk' => 'Laravel we Vue.js ulanyp doly stack goşundylaryny işläp düzdüm. Öz CRM ulgamy bilen Duolingo ýaly dil öwreniş platformasyny döretdim. Yii2 bilen işledim we bar bolan taslamalaryň öndürijiligini we ulanyjy tejribesini gowulaşdyrdym.',
                ],
            ],
            [
                'company' => 'Pikir',
                'duration' => 'Nov 2022 — Jun 2024',
                'role' => [
                    'en' => 'Backend Developer & QA Automation Engineer',
                    'ru' => 'Backend Developer & QA Automation Engineer',
                    'tk' => 'Backend Developer & QA Awtomatlaşdyrma Inženeri',
                ],
                'description' => [
                    'en' => 'Developed backend systems using Laravel for restaurant digital menu platforms. Built and maintained CRM systems, designed versioned REST APIs for tablet and React-based web apps. Successfully delivered 50+ restaurant projects. Also worked as QA Automation Engineer using Java Selenium.',
                    'ru' => 'Разрабатывал бэкенд на Laravel для платформ цифровых меню ресторанов. Создавал и поддерживал CRM-системы, проектировал версионированные REST API для планшетных и React-приложений. Успешно сдал 50+ проектов. Также занимался QA-автоматизацией на Java Selenium.',
                    'tk' => 'Restoran sanly menýu platformalary üçin Laravel ulanyp serwer ulgamlaryny işläp düzdüm. CRM ulgamlaryny döretdim we goldadym, planşet we React esasly web programmalar üçin wersiýaly REST API-leri döretdim. 50+ restoran taslamasyny üstünlikli tabşyrdym. Java Selenium ulanyp QA awtomatlaşdyrma inženeri hökmünde hem işledim.',
                ],
            ],
        ];

        foreach ($experiences as $i => $exp) {
            Experience::create([
                'company' => $exp['company'],
                'duration' => $exp['duration'],
                'role' => $exp['role'],
                'description' => $exp['description'],
                'sort_order' => $i,
            ]);
        }
    }

    private function seedProjects(): void
    {
        if (Project::query()->exists()) {
            return;
        }

        $projects = [
            [
                'title' => 'OpenDash Analytics',
                'is_private' => false,
                'tech_stack' => ['React', 'D3.js', 'Node.js', 'PostgreSQL'],
                'thumb_label' => '[ analytics dashboard — charts & filters ]',
                'desc' => [
                    'en' => 'Real-time analytics dashboard with live charts, filters, and CSV/PDF export. Multi-user workspaces.',
                    'ru' => 'Дашборд аналитики в реальном времени с живыми графиками и экспортом.',
                    'tk' => 'Janly grafikler we eksport bilen real wagt analitika paneli.',
                ],
            ],
            [
                'title' => 'UX Component Kit',
                'is_private' => false,
                'tech_stack' => ['React', 'TypeScript', 'Storybook'],
                'thumb_label' => '[ component library — buttons & forms ]',
                'desc' => [
                    'en' => 'Open-source design system with 60+ accessible React components, dark mode, and Figma kit.',
                    'ru' => 'Open-source дизайн-система с 60+ компонентами и Figma-китом.',
                    'tk' => '60+ komponent bilen açyk dizaýn ulgamy.',
                ],
            ],
            [
                'title' => 'Enterprise CRM System',
                'is_private' => true,
                'tech_stack' => ['Next.js', 'Django', 'PostgreSQL', 'Redis'],
                'thumb_label' => '[ CRM — contacts & pipeline view ]',
                'desc' => [
                    'en' => 'Custom CRM for a Fortune 500 client. Multi-tenant, role-based access, advanced reporting.',
                    'ru' => 'CRM-система для крупного корпоративного клиента. Мультитенантная архитектура.',
                    'tk' => 'Iri korporatiw müşderi üçin CRM ulgamy.',
                ],
            ],
            [
                'title' => 'AI Content Generator',
                'is_private' => false,
                'tech_stack' => ['React', 'Python', 'OpenAI API'],
                'thumb_label' => '[ AI writing tool — editor interface ]',
                'desc' => [
                    'en' => 'AI-powered writing tool with templates, team collaboration, and version history.',
                    'ru' => 'Инструмент для генерации контента с ИИ, шаблонами и командной работой.',
                    'tk' => 'AI bilen kontent generator guraly.',
                ],
            ],
            [
                'title' => 'FinTech Payment Gateway',
                'is_private' => true,
                'tech_stack' => ['Node.js', 'MongoDB', 'Stripe', 'Docker'],
                'thumb_label' => '[ payment dashboard — transactions ]',
                'desc' => [
                    'en' => 'High-throughput payment processing with fraud detection, PCI compliance, and real-time monitoring.',
                    'ru' => 'Высоконагруженный платёжный шлюз с фрод-детекцией и соответствием PCI DSS.',
                    'tk' => 'Ýokary ýüklemeli töleg gatesi.',
                ],
            ],
            [
                'title' => 'DevPortfolio Template',
                'is_private' => false,
                'tech_stack' => ['Next.js', 'Tailwind', 'Framer Motion'],
                'thumb_label' => '[ portfolio template — hero view ]',
                'desc' => [
                    'en' => 'Multilingual developer portfolio with dark mode, animations, and admin panel.',
                    'ru' => 'Шаблон портфолио с мультиязычностью, тёмной темой и админ-панелью.',
                    'tk' => 'Köp dilli portfolio şablony.',
                ],
            ],
        ];

        foreach ($projects as $i => $p) {
            Project::create([
                'title' => $p['title'],
                'title_translations' => null,
                'description' => $p['desc']['ru'],
                'description_translations' => $p['desc'],
                'type' => ProjectType::Web,
                'thumb_label' => $p['thumb_label'],
                'tech_stack' => $p['tech_stack'],
                'is_active' => true,
                'is_private' => $p['is_private'],
                'sort_order' => $i,
            ]);
        }
    }
}
