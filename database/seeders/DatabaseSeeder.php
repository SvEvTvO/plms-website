<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\CategoryGroup;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $groupedCategories = [
            '💻 Technology & Development' => [
                ['name' => 'Frontend Development', 'icon' => 'code', 'color' => '#3b82f6', 'tags' => [
                    'HTML', 'CSS', 'JavaScript', 'TypeScript', 'Responsive Design',
                    'Web Accessibility', 'Semantic Markup', 'DOM Manipulation', 'Browser APIs', 'Component Architecture',
                    'State Management', 'Client-side Routing', 'Form Handling', 'Animation', 'Web Performance',
                    'Code Splitting', 'Lazy Loading', 'Progressive Enhancement', 'Cross-browser Compatibility', 'Internationalization',
                    'Localization', 'SEO Fundamentals', 'Design Tokens', 'Reusable Components', 'Utility-first CSS',
                    'CSS Architecture', 'Build Tools', 'Package Management', 'Dependency Management', 'Frontend Testing'
                ]],
                ['name' => 'Backend Development', 'icon' => 'server', 'color' => '#3b82f6', 'tags' => [
                    'HTTP', 'Server-side Rendering', 'Routing', 'Middleware', 'Authentication',
                    'Authorization', 'Session Management', 'Caching', 'Queues', 'Background Jobs',
                    'File Uploads', 'Email Delivery', 'Webhooks', 'API Design', 'Request Validation',
                    'Rate Limiting', 'Logging', 'Error Handling', 'Configuration Management', 'Dependency Injection',
                    'Service Architecture', 'Microservices', 'Monolith Architecture', 'Message Queues', 'Real-time Communication',
                    'WebSockets', 'Search Integration', 'Payment Integration', 'Third-party Integrations', 'Backend Testing'
                ]],
                ['name' => 'Full-Stack Development', 'icon' => 'layers', 'color' => '#3b82f6', 'tags' => [
                    'Frontend Architecture', 'Backend Architecture', 'API Integration', 'Database Integration', 'Authentication Flow',
                    'Authorization Flow', 'Form Validation', 'CRUD', 'State Synchronization', 'SSR',
                    'ISR', 'Caching Strategy', 'Error Handling', 'File Storage', 'Background Processing',
                    'Email Integration', 'Search', 'Payments', 'Analytics Integration', 'Deployment',
                    'Environment Variables', 'Secrets Management', 'Testing Strategy', 'Monitoring', 'Performance Optimization',
                    'Security Practices', 'Code Organization', 'Reusable Services', 'API Contracts', 'End-to-end Testing'
                ]],
                ['name' => 'Mobile Development', 'icon' => 'device-mobile', 'color' => '#3b82f6', 'tags' => [
                    'Android', 'iOS', 'Cross-platform Apps', 'Native Apps', 'Hybrid Apps',
                    'Mobile UI', 'Responsive Layouts', 'Touch Interaction', 'Offline Support', 'Push Notifications',
                    'Deep Linking', 'App Navigation', 'State Management', 'Local Storage', 'Secure Storage',
                    'Camera Integration', 'Location Services', 'Biometrics', 'Bluetooth', 'Background Tasks',
                    'App Permissions', 'Mobile Accessibility', 'App Performance', 'Crash Reporting', 'Analytics',
                    'App Testing', 'App Distribution', 'In-app Purchases', 'Mobile Security', 'App Architecture'
                ]],
                ['name' => 'Programming Languages', 'icon' => 'braces', 'color' => '#3b82f6', 'tags' => [
                    'Python', 'JavaScript', 'TypeScript', 'Java', 'C',
                    'C++', 'C#', 'Go', 'Rust', 'PHP',
                    'Ruby', 'Kotlin', 'Swift', 'Dart', 'R',
                    'Lua', 'Scala', 'Elixir', 'Haskell', 'Perl',
                    'Functional Programming', 'Object-oriented Programming', 'Procedural Programming', 'Generics', 'Concurrency',
                    'Parallelism', 'Memory Management', 'Type Systems', 'Language Interoperability', 'Compiler Concepts'
                ]],
                ['name' => 'Software Architecture', 'icon' => 'building-arch', 'color' => '#3b82f6', 'tags' => [
                    'MVC', 'Layered Architecture', 'Clean Architecture', 'Hexagonal Architecture', 'Domain-driven Design',
                    'Event-driven Architecture', 'Service-oriented Architecture', 'Microservices', 'Monoliths', 'Modular Monoliths',
                    'CQRS', 'Event Sourcing', 'Dependency Injection', 'Design Patterns', 'SOLID Principles',
                    'Separation of Concerns', 'Domain Modeling', 'Bounded Contexts', 'API Gateways', 'Service Discovery',
                    'Resilience', 'Fault Tolerance', 'Scalability', 'High Availability', 'Observability',
                    'Architecture Decision Records', 'Distributed Systems', 'System Design', 'Data Consistency', 'Distributed Transactions'
                ]],
                ['name' => 'API & Integrations', 'icon' => 'plug-connected', 'color' => '#3b82f6', 'tags' => [
                    'REST', 'GraphQL', 'gRPC', 'SOAP', 'OpenAPI',
                    'Webhooks', 'OAuth', 'API Keys', 'JWT', 'Pagination',
                    'Filtering', 'Sorting', 'Rate Limiting', 'Versioning', 'API Gateways',
                    'Schema Design', 'Request Validation', 'Error Contracts', 'Idempotency', 'Retries',
                    'Circuit Breakers', 'Caching', 'Streaming APIs', 'Real-time APIs', 'Third-party APIs',
                    'SDK Integration', 'Data Synchronization', 'ETL Integrations', 'Webhook Security', 'API Testing'
                ]],
                ['name' => 'Database', 'icon' => 'database', 'color' => '#3b82f6', 'tags' => [
                    'Relational Databases', 'Document Databases', 'Key-value Stores', 'Graph Databases', 'SQL',
                    'NoSQL', 'Database Design', 'Normalization', 'Denormalization', 'Indexes',
                    'Transactions', 'Constraints', 'Foreign Keys', 'Joins', 'Views',
                    'Stored Procedures', 'Triggers', 'Query Optimization', 'Migrations', 'Seeding',
                    'Replication', 'Sharding', 'Partitioning', 'Backups', 'Recovery',
                    'Data Modeling', 'Connection Pooling', 'Database Security', 'Data Integrity', 'Database Monitoring'
                ]],
                ['name' => 'DevOps & CI/CD', 'icon' => 'git-merge', 'color' => '#3b82f6', 'tags' => [
                    'CI/CD', 'Build Pipelines', 'Release Automation', 'Infrastructure as Code', 'Configuration Management',
                    'Containerization', 'Container Orchestration', 'Service Discovery', 'Secrets Management', 'Artifact Management',
                    'Blue-green Deployment', 'Canary Deployment', 'Rolling Deployment', 'Feature Flags', 'Environment Management',
                    'Branch Strategies', 'Automated Testing', 'Deployment Gates', 'Observability', 'Log Aggregation',
                    'Metrics', 'Tracing', 'Incident Response', 'Infrastructure Monitoring', 'Disaster Recovery',
                    'High Availability', 'Scalability', 'GitOps', 'Platform Engineering', 'DevSecOps'
                ]],
                ['name' => 'Cloud Computing', 'icon' => 'cloud', 'color' => '#3b82f6', 'tags' => [
                    'Cloud Infrastructure', 'Virtual Machines', 'Containers', 'Serverless', 'Object Storage',
                    'Block Storage', 'Managed Databases', 'Load Balancing', 'Auto Scaling', 'Networking',
                    'DNS', 'CDN', 'Identity and Access Management', 'Secrets', 'Key Management',
                    'Monitoring', 'Logging', 'Functions', 'Queues', 'Event Streaming',
                    'Caching', 'Cloud Security', 'Cost Optimization', 'Resource Management', 'Infrastructure as Code',
                    'Multi-region', 'High Availability', 'Disaster Recovery', 'Backup Strategy', 'Cloud Migration'
                ]],
            ],
            '🤖 AI & Data' => [
                ['name' => 'AI Tools', 'icon' => 'sparkles', 'color' => '#10b981', 'tags' => [
                    'AI Assistants', 'AI Automation', 'AI Search', 'AI Research', 'AI Summarization',
                    'AI Translation', 'AI Classification', 'AI Extraction', 'AI Recommendation', 'AI Agents',
                    'Multimodal AI', 'Text Generation', 'Image Generation', 'Video Generation', 'Audio Generation',
                    'Speech Recognition', 'Text-to-speech', 'Retrieval Augmentation', 'Knowledge Bases', 'Semantic Search',
                    'Embeddings', 'Workflow Automation', 'AI Evaluation', 'Model Comparison', 'Model Fine-tuning',
                    'Prompt Design', 'Context Management', 'AI Safety', 'Human-in-the-loop', 'AI Productivity'
                ]],
                ['name' => 'Generative AI', 'icon' => 'wand', 'color' => '#10b981', 'tags' => [
                    'Text Generation', 'Image Generation', 'Video Generation', 'Audio Generation', 'Code Generation',
                    'Multimodal Generation', 'Style Transfer', 'Content Transformation', 'Summarization', 'Rewriting',
                    'Ideation', 'Creative Assistance', 'Prompt Chaining', 'Prompt Templates', 'Few-shot Prompting',
                    'Zero-shot Prompting', 'Context Engineering', 'Structured Outputs', 'Function Calling', 'Tool Use',
                    'Agent Workflows', 'Retrieval Augmentation', 'Model Routing', 'Output Evaluation', 'Guardrails',
                    'Content Filtering', 'Synthetic Data', 'Personalization', 'Batch Generation', 'Generative Workflows'
                ]],
                ['name' => 'AI Chat', 'icon' => 'message-circle', 'color' => '#10b981', 'tags' => [
                    'Conversational AI', 'Question Answering', 'Instruction Following', 'Context Awareness', 'Conversation Memory',
                    'Role-based Prompting', 'Knowledge-grounded Answers', 'Document Q&A', 'Research Assistance', 'Study Assistance',
                    'Brainstorming', 'Writing Assistance', 'Coding Assistance', 'Translation', 'Summarization',
                    'Classification', 'Extraction', 'Sentiment Analysis', 'Structured Responses', 'Function Calling',
                    'Tool Calling', 'Multi-turn Dialogues', 'Voice Interaction', 'Multimodal Input', 'Response Evaluation',
                    'Hallucination Reduction', 'Citation Support', 'Prompt Templates', 'Conversation Design', 'AI Ethics'
                ]],
                ['name' => 'AI Image Generation', 'icon' => 'photo-ai', 'color' => '#10b981', 'tags' => [
                    'Text-to-image', 'Image-to-image', 'Inpainting', 'Outpainting', 'Image Editing',
                    'Background Removal', 'Background Generation', 'Upscaling', 'Super Resolution', 'Style Transfer',
                    'Character Generation', 'Character Consistency', 'Pose Control', 'Composition Control', 'Depth Control',
                    'Edge Control', 'Color Control', 'Lighting Control', 'Typography in Images', 'Product Visualization',
                    'Concept Art', 'Illustration', 'Photorealism', '3D-style Rendering', 'Texture Generation',
                    'Mockup Generation', 'Variation Generation', 'Image Restoration', 'Creative Retouching', 'Batch Generation'
                ]],
                ['name' => 'AI Video Generation', 'icon' => 'video', 'color' => '#10b981', 'tags' => [
                    'Text-to-video', 'Image-to-video', 'Video-to-video', 'Video Editing', 'Scene Generation',
                    'Character Animation', 'Motion Generation', 'Lip Sync', 'Camera Control', 'Motion Control',
                    'Style Transfer', 'Video Upscaling', 'Frame Interpolation', 'Background Replacement', 'Object Removal',
                    'Object Tracking', 'Subtitle Generation', 'Voiceover Generation', 'Sound Generation', 'Storyboard Generation',
                    'Shot Planning', 'Product Videos', 'Short-form Video', 'Visual Effects', 'Virtual Presenters',
                    'Animation Generation', 'Video Restoration', 'Color Enhancement', 'Batch Rendering', 'Video Consistency'
                ]],
                ['name' => 'AI Audio & Voice', 'icon' => 'microphone', 'color' => '#10b981', 'tags' => [
                    'Speech-to-text', 'Text-to-speech', 'Voice Generation', 'Voice Conversion', 'Voice Cloning',
                    'Audio Enhancement', 'Noise Reduction', 'Audio Restoration', 'Music Generation', 'Sound Effects',
                    'Voiceover', 'Dubbing', 'Transcription', 'Speaker Diarization', 'Emotion Detection',
                    'Pronunciation Support', 'Audio Editing', 'Audio Mixing', 'Mastering', 'Background Music',
                    'Beat Generation', 'Audio Separation', 'Vocal Isolation', 'Instrument Isolation', 'Podcast Processing',
                    'Real-time Voice', 'Voice Assistants', 'Audio Summarization', 'Speech Analysis', 'Voice Safety'
                ]],
                ['name' => 'Machine Learning', 'icon' => 'chart-dots-3', 'color' => '#10b981', 'tags' => [
                    'Supervised Learning', 'Unsupervised Learning', 'Semi-supervised Learning', 'Reinforcement Learning', 'Classification',
                    'Regression', 'Clustering', 'Dimensionality Reduction', 'Feature Engineering', 'Feature Selection',
                    'Model Training', 'Model Evaluation', 'Cross-validation', 'Hyperparameter Tuning', 'Model Selection',
                    'Ensemble Methods', 'Decision Trees', 'Neural Networks', 'Time Series', 'Anomaly Detection',
                    'Recommendation Systems', 'Predictive Modeling', 'Imbalanced Data', 'Data Augmentation', 'Model Explainability',
                    'Model Deployment', 'Model Monitoring', 'Experiment Tracking', 'Reproducibility', 'MLOps'
                ]],
                ['name' => 'Deep Learning', 'icon' => 'brain', 'color' => '#10b981', 'tags' => [
                    'Neural Networks', 'CNN', 'RNN', 'LSTM', 'GRU',
                    'Transformers', 'Attention Mechanisms', 'Autoencoders', 'GANs', 'Diffusion Models',
                    'Transfer Learning', 'Fine-tuning', 'Representation Learning', 'Self-supervised Learning', 'Object Detection',
                    'Image Segmentation', 'Sequence Modeling', 'Speech Recognition', 'Text Classification', 'Multimodal Learning',
                    'Embeddings', 'Vector Search', 'Model Compression', 'Quantization', 'Pruning',
                    'Distillation', 'GPU Acceleration', 'Distributed Training', 'Inference Optimization', 'Experiment Tracking'
                ]],
                ['name' => 'NLP & Language', 'icon' => 'language', 'color' => '#10b981', 'tags' => [
                    'Tokenization', 'Text Classification', 'Named Entity Recognition', 'Sentiment Analysis', 'Text Summarization',
                    'Machine Translation', 'Question Answering', 'Text Similarity', 'Semantic Search', 'Embeddings',
                    'Topic Modeling', 'Keyword Extraction', 'Information Extraction', 'Language Detection', 'Spell Checking',
                    'Grammar Checking', 'Text Generation', 'Text Completion', 'Document Analysis', 'Dialogue Systems',
                    'Conversational Search', 'Information Retrieval', 'Retrieval Augmentation', 'Prompt Design', 'Language Evaluation',
                    'Speech Recognition', 'Text-to-speech', 'Corpus Analysis', 'Multilingual NLP', 'Language Modeling'
                ]],
                ['name' => 'Computer Vision', 'icon' => 'eye', 'color' => '#10b981', 'tags' => [
                    'Image Classification', 'Object Detection', 'Image Segmentation', 'Instance Segmentation', 'Pose Estimation',
                    'Face Detection', 'Face Recognition', 'Optical Character Recognition', 'Image Similarity', 'Visual Search',
                    'Image Retrieval', 'Scene Understanding', 'Depth Estimation', 'Image Captioning', 'Visual Question Answering',
                    'Tracking', 'Motion Detection', 'Anomaly Detection', 'Image Enhancement', 'Super Resolution',
                    'Image Restoration', 'Image Registration', '3D Vision', 'Document Vision', 'Medical Imaging',
                    'Remote Sensing', 'Video Analysis', 'Visual Embeddings', 'Edge Detection', 'Feature Extraction'
                ]],
            ],
            '🎨 Design & Creative' => [
                ['name' => 'UI/UX Design', 'icon' => 'palette', 'color' => '#f59e0b', 'tags' => [
                    'User Research', 'Personas', 'User Journeys', 'Information Architecture', 'User Flows',
                    'Wireframing', 'Prototyping', 'Usability Testing', 'Interaction Design', 'Visual Hierarchy',
                    'Layout Design', 'Responsive Design', 'Design Systems', 'Design Tokens', 'Component Libraries',
                    'Accessibility', 'Microinteractions', 'Motion Design', 'Content Hierarchy', 'UX Writing',
                    'Navigation Design', 'Form Design', 'Dashboard Design', 'Mobile UX', 'Web UX',
                    'Design Handoff', 'Usability Heuristics', 'A/B Testing', 'Design Documentation', 'Product Discovery'
                ]],
                ['name' => 'Web Design', 'icon' => 'browser', 'color' => '#f59e0b', 'tags' => [
                    'Landing Pages', 'Marketing Websites', 'Portfolio Websites', 'SaaS Websites', 'Corporate Websites',
                    'E-commerce Websites', 'Editorial Websites', 'Dashboard Interfaces', 'Responsive Layouts', 'Mobile-first Design',
                    'Grid Systems', 'Visual Hierarchy', 'Typography', 'Color Systems', 'Spacing Systems',
                    'Design Tokens', 'Navigation', 'Hero Sections', 'Call-to-action', 'Forms',
                    'Cards', 'Tables', 'Modals', 'Animations', 'Microinteractions',
                    'Accessibility', 'Performance', 'SEO', 'Content Structure', 'Conversion Design'
                ]],
                ['name' => 'Graphic Design', 'icon' => 'brush', 'color' => '#f59e0b', 'tags' => [
                    'Poster Design', 'Flyer Design', 'Brochure Design', 'Social Media Graphics', 'Presentation Design',
                    'Editorial Design', 'Print Design', 'Layout Design', 'Visual Composition', 'Color Theory',
                    'Typography', 'Illustration', 'Icon Design', 'Infographics', 'Photo Editing',
                    'Image Compositing', 'Brand Collateral', 'Advertising Creative', 'Packaging Graphics', 'Signage',
                    'Publication Design', 'Marketing Materials', 'Event Graphics', 'Digital Banners', 'Social Templates',
                    'Creative Direction', 'Art Direction', 'Visual Storytelling', 'Print Preparation', 'Design Grids'
                ]],
                ['name' => 'Branding & Identity', 'icon' => 'badge', 'color' => '#f59e0b', 'tags' => [
                    'Brand Strategy', 'Brand Positioning', 'Naming', 'Logo Design', 'Visual Identity',
                    'Brand Guidelines', 'Color Palette', 'Typography System', 'Iconography', 'Photography Direction',
                    'Illustration Style', 'Brand Voice', 'Tone of Voice', 'Messaging', 'Brand Architecture',
                    'Sub-branding', 'Packaging Identity', 'Digital Identity', 'Motion Identity', 'Social Identity',
                    'Marketing Collateral', 'Stationery', 'Presentation Templates', 'Environmental Branding', 'Brand Applications',
                    'Competitive Positioning', 'Audience Research', 'Brand Consistency', 'Rebranding', 'Brand Audits'
                ]],
                ['name' => 'Typography', 'icon' => 'typography', 'color' => '#f59e0b', 'tags' => [
                    'Font Pairing', 'Type Scale', 'Variable Fonts', 'Serif', 'Sans Serif',
                    'Monospace', 'Display Type', 'Body Type', 'Readability', 'Legibility',
                    'Hierarchy', 'Kerning', 'Tracking', 'Leading', 'Letterforms',
                    'Glyphs', 'OpenType Features', 'Web Fonts', 'Responsive Typography', 'Editorial Typography',
                    'Interface Typography', 'Brand Typography', 'Accessibility', 'Multilingual Type', 'Script Support',
                    'Font Licensing', 'Font Classification', 'Text Composition', 'Type Specimens', 'Typography Systems'
                ]],
                ['name' => 'Illustration', 'icon' => 'pencil', 'color' => '#f59e0b', 'tags' => [
                    'Editorial Illustration', 'Character Illustration', 'Icon Illustration', 'Vector Illustration', 'Digital Painting',
                    'Concept Illustration', 'Isometric Illustration', 'Flat Illustration', '3D Illustration', 'Line Art',
                    'Hand-drawn Style', 'Collage', 'Pattern Design', 'Mascot Design', 'Infographic Illustration',
                    'Spot Illustration', 'Editorial Covers', 'Technical Illustration', 'Medical Illustration', 'Children’s Illustration',
                    'Storytelling', 'Visual Metaphors', 'Character Design', 'Shape Language', 'Color Exploration',
                    'Texture', 'Composition', 'Perspective', 'Illustration Systems', 'Asset Libraries'
                ]],
                ['name' => '3D Design', 'icon' => 'box', 'color' => '#f59e0b', 'tags' => [
                    '3D Modeling', 'Sculpting', 'Texturing', 'UV Mapping', 'Rigging',
                    'Skinning', 'Animation', 'Rendering', 'Lighting', 'Materials',
                    'Shading', 'Geometry Nodes', 'Procedural Modeling', 'Hard Surface Modeling', 'Organic Modeling',
                    'Product Visualization', 'Architectural Visualization', 'Character Modeling', 'Environment Design', 'Photorealistic Rendering',
                    'Real-time Rendering', '3D Assets', 'CAD Conversion', 'Motion Graphics 3D', 'Simulation',
                    'Particles', 'Cloth Simulation', 'Camera Animation', 'Compositing', 'Asset Optimization'
                ]],
                ['name' => 'Motion Design', 'icon' => 'movie', 'color' => '#f59e0b', 'tags' => [
                    'Keyframe Animation', 'Motion Graphics', 'UI Animation', 'Microinteractions', 'Logo Animation',
                    'Kinetic Typography', 'Transitions', 'Character Animation', '2D Animation', '3D Animation',
                    'Particle Effects', 'Morphing', 'Camera Movement', 'Easing', 'Timing',
                    'Storyboarding', 'Title Sequences', 'Explainer Animation', 'Social Motion', 'Advertisement Motion',
                    'Product Animation', 'Screen Animation', 'Visual Effects', 'Compositing', 'Rotoscoping',
                    'Motion Tracking', 'Color Grading', 'Sound Sync', 'Looping Animation', 'Animated Infographics'
                ]],
                ['name' => 'Video Editing', 'icon' => 'film', 'color' => '#f59e0b', 'tags' => [
                    'Timeline Editing', 'Cutting', 'Trimming', 'Transitions', 'Color Correction',
                    'Color Grading', 'Audio Editing', 'Audio Mixing', 'Subtitles', 'Captions',
                    'Motion Graphics', 'Keying', 'Masking', 'Rotoscoping', 'Motion Tracking',
                    'Stabilization', 'Speed Ramping', 'Time Remapping', 'Multi-camera Editing', 'Proxy Editing',
                    'Media Management', 'Scene Detection', 'Noise Reduction', 'Video Upscaling', 'Frame Interpolation',
                    'Aspect Ratio Conversion', 'Social Video', 'Short-form Video', 'Long-form Video', 'Export Optimization'
                ]],
                ['name' => 'Photography', 'icon' => 'camera', 'color' => '#f59e0b', 'tags' => [
                    'Portrait Photography', 'Landscape Photography', 'Street Photography', 'Product Photography', 'Food Photography',
                    'Architecture Photography', 'Event Photography', 'Travel Photography', 'Macro Photography', 'Astrophotography',
                    'Documentary Photography', 'Studio Lighting', 'Natural Lighting', 'Composition', 'Exposure',
                    'Color Grading', 'Photo Retouching', 'Image Compositing', 'RAW Processing', 'Lens Correction',
                    'Perspective Correction', 'Background Removal', 'Photo Restoration', 'Batch Editing', 'Print Preparation',
                    'Photo Organization', 'Stock Photography', 'Photo Presets', 'Visual Storytelling', 'Photo Workflow'
                ]],
            ],
            '📈 Business & Marketing' => [
                ['name' => 'Digital Marketing', 'icon' => 'rocket', 'color' => '#6366f1', 'tags' => [
                    'SEO', 'Search Advertising', 'Display Advertising', 'Social Advertising', 'Email Marketing',
                    'Content Marketing', 'Influencer Marketing', 'Affiliate Marketing', 'Conversion Optimization', 'Lead Generation',
                    'Marketing Automation', 'Retargeting', 'Audience Segmentation', 'Customer Acquisition', 'Customer Retention',
                    'Marketing Analytics', 'Attribution', 'Campaign Planning', 'Content Distribution', 'Landing Page Optimization',
                    'Funnel Optimization', 'Growth Marketing', 'Performance Marketing', 'Local Marketing', 'Mobile Marketing',
                    'Community Marketing', 'Referral Marketing', 'Partnership Marketing', 'Marketing Strategy', 'Experimentation'
                ]],
                ['name' => 'SEO', 'icon' => 'search', 'color' => '#6366f1', 'tags' => [
                    'Keyword Research', 'On-page SEO', 'Technical SEO', 'Link Building', 'Internal Linking',
                    'Site Architecture', 'Structured Data', 'Schema Markup', 'Crawling', 'Indexing',
                    'Sitemaps', 'Canonicalization', 'Page Speed', 'Core Web Vitals', 'Mobile SEO',
                    'Local SEO', 'International SEO', 'Content Optimization', 'Search Intent', 'SERP Analysis',
                    'Rank Tracking', 'Backlink Analysis', 'Competitor Research', 'Log Analysis', 'SEO Audits',
                    'Image SEO', 'Video SEO', 'E-commerce SEO', 'SEO Reporting', 'Search Visibility'
                ]],
                ['name' => 'Content Marketing', 'icon' => 'article', 'color' => '#6366f1', 'tags' => [
                    'Content Strategy', 'Editorial Planning', 'Blogging', 'Long-form Content', 'Short-form Content',
                    'Pillar Content', 'Topic Clusters', 'Content Repurposing', 'Content Distribution', 'Audience Research',
                    'Content Calendars', 'SEO Content', 'Educational Content', 'Thought Leadership', 'Case Studies',
                    'Whitepapers', 'Lead Magnets', 'Newsletters', 'Social Content', 'Video Content',
                    'Interactive Content', 'User-generated Content', 'Content Curation', 'Content Audits', 'Content Measurement',
                    'Engagement Metrics', 'Conversion Content', 'Storytelling', 'Brand Publishing', 'Content Operations'
                ]],
                ['name' => 'Copywriting', 'icon' => 'writing', 'color' => '#6366f1', 'tags' => [
                    'Ad Copy', 'Landing Page Copy', 'Website Copy', 'Email Copy', 'Product Descriptions',
                    'Headlines', 'Call-to-action', 'Value Propositions', 'Taglines', 'Sales Copy',
                    'Direct Response', 'Storytelling', 'Brand Messaging', 'UX Copy', 'Microcopy',
                    'Social Media Copy', 'Video Scripts', 'Email Sequences', 'Lead Magnets', 'Case Study Copy',
                    'Long-form Sales Copy', 'Short-form Copy', 'Persuasive Writing', 'Audience Segmentation', 'Tone of Voice',
                    'Editing', 'Proofreading', 'Conversion Writing', 'A/B Testing', 'Copy Research'
                ]],
                ['name' => 'Social Media', 'icon' => 'brand-instagram', 'color' => '#6366f1', 'tags' => [
                    'Content Planning', 'Content Calendars', 'Community Management', 'Social Listening', 'Short-form Video',
                    'Stories', 'Live Streaming', 'Audience Engagement', 'Influencer Campaigns', 'Hashtag Strategy',
                    'Social Advertising', 'Organic Growth', 'Creator Partnerships', 'Trend Research', 'Social Analytics',
                    'Content Repurposing', 'Visual Content', 'User-generated Content', 'Moderation', 'Publishing Workflows',
                    'Cross-platform Strategy', 'Brand Voice', 'Community Building', 'Campaign Management', 'Social SEO',
                    'Engagement Rate', 'Follower Growth', 'Conversion Tracking', 'Content Scheduling', 'Social Reporting'
                ]],
                ['name' => 'E-commerce', 'icon' => 'shopping-cart', 'color' => '#6366f1', 'tags' => [
                    'Product Catalogs', 'Product Pages', 'Shopping Carts', 'Checkout', 'Payment Processing',
                    'Order Management', 'Inventory Management', 'Shipping', 'Returns', 'Discounts',
                    'Coupons', 'Subscriptions', 'Product Bundles', 'Cross-selling', 'Upselling',
                    'Reviews', 'Ratings', 'Wishlists', 'Customer Accounts', 'Guest Checkout',
                    'Abandoned Carts', 'Search', 'Product Filters', 'Product Recommendations', 'Marketplace Integration',
                    'Tax Calculation', 'Multi-currency', 'Multi-language', 'E-commerce Analytics', 'Conversion Optimization'
                ]],
                ['name' => 'Startup & SaaS', 'icon' => 'building-store', 'color' => '#6366f1', 'tags' => [
                    'Idea Validation', 'Market Research', 'MVP Development', 'Product Discovery', 'Product-market Fit',
                    'Business Models', 'SaaS Metrics', 'Pricing Strategy', 'Customer Interviews', 'Customer Development',
                    'Go-to-market', 'Pitch Decks', 'Fundraising', 'Venture Capital', 'Bootstrapping',
                    'Product Roadmaps', 'Growth Loops', 'Retention', 'Churn', 'Activation',
                    'Acquisition', 'Monetization', 'Unit Economics', 'Cohort Analysis', 'Competitive Analysis',
                    'Partnerships', 'Founder Operations', 'Startup Finance', 'Scaling', 'Remote Teams'
                ]],
                ['name' => 'Sales & CRM', 'icon' => 'users-group', 'color' => '#6366f1', 'tags' => [
                    'Lead Management', 'Pipeline Management', 'Deal Tracking', 'Sales Forecasting', 'Prospecting',
                    'Lead Qualification', 'Outbound Sales', 'Inbound Sales', 'Sales Outreach', 'Email Sequences',
                    'Call Tracking', 'Meeting Management', 'Account Management', 'Customer Segmentation', 'Sales Automation',
                    'CRM Workflows', 'Contact Management', 'Opportunity Management', 'Quoting', 'Proposals',
                    'Negotiation', 'Sales Enablement', 'Territory Management', 'Commission Tracking', 'Customer Retention',
                    'Cross-selling', 'Upselling', 'Sales Analytics', 'Revenue Forecasting', 'Pipeline Reporting'
                ]],
                ['name' => 'Product Management', 'icon' => 'roadmap', 'color' => '#6366f1', 'tags' => [
                    'Product Discovery', 'User Research', 'Problem Framing', 'Opportunity Mapping', 'Product Strategy',
                    'Roadmapping', 'Prioritization', 'Backlog Management', 'Requirements', 'User Stories',
                    'Acceptance Criteria', 'Product Specs', 'Prototyping', 'Experimentation', 'A/B Testing',
                    'Metrics', 'Product Analytics', 'Feature Planning', 'Release Planning', 'Go-to-market',
                    'Stakeholder Management', 'Product Operations', 'Customer Feedback', 'Competitive Analysis', 'Product Positioning',
                    'Pricing', 'Lifecycle Management', 'Product-led Growth', 'Retention', 'Product Reviews'
                ]],
                ['name' => 'Business Intelligence', 'icon' => 'chart-bar', 'color' => '#6366f1', 'tags' => [
                    'Dashboards', 'KPIs', 'Data Warehousing', 'Reporting', 'Data Modeling',
                    'ETL', 'ELT', 'Data Integration', 'Metrics', 'Forecasting',
                    'Trend Analysis', 'Drill-down Analysis', 'Interactive Reports', 'Executive Reporting', 'Operational Reporting',
                    'Self-service BI', 'Data Governance', 'Data Quality', 'Data Catalogs', 'Semantic Layers',
                    'Ad Hoc Analysis', 'Benchmarking', 'Cohort Analysis', 'Funnel Analysis', 'Segmentation',
                    'Attribution', 'Performance Monitoring', 'Alerts', 'Scheduled Reports', 'Decision Support'
                ]],
            ],
            '📚 Learning & Research' => [
                ['name' => 'Online Learning', 'icon' => 'school', 'color' => '#ec4899', 'tags' => [
                    'Video Courses', 'Interactive Courses', 'Tutorials', 'Bootcamps', 'Certifications',
                    'Practice Exercises', 'Quizzes', 'Flashcards', 'Assignments', 'Project-based Learning',
                    'Self-paced Learning', 'Live Classes', 'Study Plans', 'Learning Paths', 'Skill Assessments',
                    'Progress Tracking', 'Peer Learning', 'Mentorship', 'Discussion Forums', 'Code Labs',
                    'Virtual Classrooms', 'Recorded Lectures', 'Course Notes', 'Study Guides', 'Exam Preparation',
                    'Language Practice', 'Microlearning', 'Spaced Repetition', 'Learning Analytics', 'Open Courses'
                ]],
                ['name' => 'Programming Education', 'icon' => 'school', 'color' => '#ec4899', 'tags' => [
                    'Coding Tutorials', 'Algorithm Practice', 'Data Structures', 'Web Development', 'App Development',
                    'Game Programming', 'Database Practice', 'Debugging Exercises', 'Code Challenges', 'Interactive Coding',
                    'Project Tutorials', 'Code Review', 'Testing Practice', 'Version Control', 'API Practice',
                    'System Design', 'Architecture Lessons', 'Security Practice', 'DevOps Practice', 'Cloud Practice',
                    'Interview Preparation', 'Beginner Programming', 'Advanced Programming', 'Programming Exercises', 'Documentation Reading',
                    'Technical Writing', 'Pair Programming', 'Open Source Learning', 'Competitive Programming', 'Computer Science Fundamentals'
                ]],
                ['name' => 'Academic Research', 'icon' => 'book-2', 'color' => '#ec4899', 'tags' => [
                    'Literature Review', 'Research Papers', 'Citation Management', 'Reference Management', 'Research Databases',
                    'Academic Search', 'Research Methods', 'Qualitative Research', 'Quantitative Research', 'Data Collection',
                    'Survey Design', 'Statistical Analysis', 'Bibliographic Search', 'Systematic Reviews', 'Meta-analysis',
                    'Research Ethics', 'Plagiarism Checking', 'Academic Writing', 'Thesis Writing', 'Dissertation Writing',
                    'Peer Review', 'Preprints', 'Open Access', 'Research Collaboration', 'Research Repositories',
                    'Dataset Discovery', 'Research Visualization', 'Reproducible Research', 'Evidence Synthesis', 'Research Notes'
                ]],
                ['name' => 'Mathematics', 'icon' => 'math', 'color' => '#ec4899', 'tags' => [
                    'Algebra', 'Geometry', 'Calculus', 'Trigonometry', 'Statistics',
                    'Probability', 'Discrete Mathematics', 'Linear Algebra', 'Number Theory', 'Differential Equations',
                    'Mathematical Logic', 'Optimization', 'Combinatorics', 'Graph Theory', 'Set Theory',
                    'Numerical Methods', 'Mathematical Proofs', 'Problem Solving', 'Symbolic Computation', 'Numerical Computation',
                    'Mathematical Modeling', 'Data Analysis', 'Coordinate Geometry', 'Vectors', 'Matrices',
                    'Sequences', 'Series', 'Functions', 'Theorem Proving', 'Math Visualization'
                ]],
                ['name' => 'Science', 'icon' => 'atom', 'color' => '#ec4899', 'tags' => [
                    'Physics', 'Chemistry', 'Biology', 'Astronomy', 'Earth Science',
                    'Environmental Science', 'Materials Science', 'Neuroscience', 'Genetics', 'Microbiology',
                    'Ecology', 'Geology', 'Meteorology', 'Oceanography', 'Thermodynamics',
                    'Electromagnetism', 'Mechanics', 'Quantum Physics', 'Organic Chemistry', 'Inorganic Chemistry',
                    'Biochemistry', 'Cell Biology', 'Evolution', 'Scientific Visualization', 'Laboratory Methods',
                    'Scientific Data', 'Research Methods', 'Science Communication', 'Open Science', 'Citizen Science'
                ]],
                ['name' => 'Language Learning', 'icon' => 'language-hiragana', 'color' => '#ec4899', 'tags' => [
                    'Vocabulary', 'Grammar', 'Pronunciation', 'Listening Practice', 'Speaking Practice',
                    'Reading Practice', 'Writing Practice', 'Conversation Practice', 'Flashcards', 'Spaced Repetition',
                    'Translation Practice', 'Dictation', 'Comprehension', 'Language Exchange', 'Immersion',
                    'Beginner Lessons', 'Intermediate Lessons', 'Advanced Lessons', 'Business Language', 'Academic Language',
                    'Travel Language', 'Idioms', 'Phrasal Verbs', 'Verb Conjugation', 'Sentence Building',
                    'Accent Training', 'Language Assessment', 'Exam Preparation', 'Multilingual Learning', 'Language Resources'
                ]],
                ['name' => 'Writing & Publishing', 'icon' => 'book', 'color' => '#ec4899', 'tags' => [
                    'Creative Writing', 'Technical Writing', 'Academic Writing', 'Blog Writing', 'Essay Writing',
                    'Report Writing', 'Copy Editing', 'Proofreading', 'Grammar', 'Style Guides',
                    'Outlining', 'Research Notes', 'Citation Styles', 'Publishing Workflows', 'Self-publishing',
                    'Digital Publishing', 'Print Publishing', 'Manuscript Formatting', 'Content Editing', 'Fact Checking',
                    'Plagiarism Prevention', 'Version Tracking', 'Collaboration', 'Editorial Calendars', 'Writing Prompts',
                    'Story Structure', 'Character Development', 'Narrative Design', 'Writing Productivity', 'Publishing Metadata'
                ]],
            ],
            '🎮 Games & Entertainment' => [
                ['name' => 'Game Development', 'icon' => 'device-gamepad-2', 'color' => '#f43f5e', 'tags' => [
                    'Game Engines', 'Gameplay Programming', 'Game Physics', 'Character Controllers', 'NPC AI',
                    'Pathfinding', 'Game UI', 'Dialogue Systems', 'Inventory Systems', 'Quest Systems',
                    'Save Systems', 'Multiplayer', 'Networking', 'Matchmaking', 'Level Design',
                    'Game Design', 'Game Balancing', 'Procedural Generation', 'Animation Systems', 'Particle Systems',
                    'Lighting', 'Shaders', 'Audio Systems', 'Input Systems', 'Controller Support',
                    'Performance Optimization', 'Mobile Games', 'PC Games', 'Console Games', 'Game Testing'
                ]],
                ['name' => 'Game Design', 'icon' => 'layout-grid', 'color' => '#f43f5e', 'tags' => [
                    'Core Mechanics', 'Game Loops', 'Level Design', 'World Building', 'Quest Design',
                    'Combat Design', 'Progression Systems', 'Economy Design', 'Reward Systems', 'Difficulty Curves',
                    'Player Motivation', 'Onboarding', 'Tutorial Design', 'Narrative Design', 'Dialogue Design',
                    'Character Design', 'Enemy Design', 'Boss Design', 'Puzzle Design', 'Social Mechanics',
                    'Multiplayer Design', 'Live Operations', 'Monetization', 'Retention', 'Accessibility',
                    'Game Feel', 'Balancing', 'Playtesting', 'UX Design', 'Game Documentation'
                ]],
                ['name' => 'Indie Game Development', 'icon' => 'rocket', 'color' => '#f43f5e', 'tags' => [
                    'Solo Development', 'Small Teams', 'Rapid Prototyping', 'MVP Games', 'Game Jams',
                    'Asset Reuse', 'Procedural Content', '2D Games', '3D Games', 'Pixel Art',
                    'Stylized Art', 'Sound Design', 'Open Source Games', 'Community Building', 'Early Access',
                    'Playtesting', 'Feedback Loops', 'Storefront Preparation', 'Marketing', 'Wishlists',
                    'Press Kits', 'Demo Releases', 'Release Planning', 'Post-launch Updates', 'Player Support',
                    'Analytics', 'Monetization', 'Publishing', 'Scope Management', 'Production Planning'
                ]],
                ['name' => 'Gaming Communities', 'icon' => 'users', 'color' => '#f43f5e', 'tags' => [
                    'Forums', 'Community Servers', 'Guilds', 'Clans', 'Leaderboards',
                    'Tournaments', 'Player Guides', 'Walkthroughs', 'Build Guides', 'Strategy Guides',
                    'Modding Communities', 'Fan Art', 'Cosplay', 'Streaming Communities', 'Esports Communities',
                    'Game Reviews', 'Game Discussions', 'Patch Discussions', 'Community Events', 'Giveaways',
                    'Developer Communities', 'Player Feedback', 'Bug Reporting', 'Feature Requests', 'Game Recommendations',
                    'Social Groups', 'Competitive Play', 'Co-op Play', 'Local Multiplayer', 'Online Multiplayer'
                ]],
                ['name' => 'Streaming & Video', 'icon' => 'broadcast', 'color' => '#f43f5e', 'tags' => [
                    'Live Streaming', 'Video Streaming', 'Short-form Video', 'Long-form Video', 'Video Discovery',
                    'Channel Management', 'Creator Profiles', 'Subscriptions', 'Live Chat', 'Moderation',
                    'Video Clips', 'Playlists', 'Video Search', 'Recommendations', 'Content Scheduling',
                    'Audience Analytics', 'Viewer Engagement', 'Monetization', 'Donations', 'Memberships',
                    'Sponsorships', 'Creator Tools', 'Broadcasting', 'Screen Capture', 'Game Capture',
                    'Webcam Overlays', 'Stream Alerts', 'Chat Bots', 'Video Chapters', 'Accessibility'
                ]],
                ['name' => 'Music & Audio', 'icon' => 'music', 'color' => '#f43f5e', 'tags' => [
                    'Music Discovery', 'Music Streaming', 'Playlists', 'Audio Streaming', 'Podcasting',
                    'Music Production', 'Beat Making', 'Songwriting', 'Recording', 'Mixing',
                    'Mastering', 'Sound Design', 'Sampling', 'Audio Effects', 'Instrument Practice',
                    'Music Theory', 'Sheet Music', 'Composition', 'Arrangement', 'Live Performance',
                    'Radio', 'DJ Tools', 'Audio Libraries', 'Sound Effects', 'Field Recording',
                    'Podcast Editing', 'Voice Recording', 'Audio Restoration', 'Audio Visualization', 'Music Education'
                ]],
                ['name' => 'Movies & TV', 'icon' => 'device-tv', 'color' => '#f43f5e', 'tags' => [
                    'Movie Discovery', 'TV Discovery', 'Watchlists', 'Recommendations', 'Trailers',
                    'Reviews', 'Ratings', 'Genres', 'Collections', 'Release Calendars',
                    'Streaming Guides', 'Episode Tracking', 'Series Tracking', 'Cast Information', 'Crew Information',
                    'Awards', 'Box Office', 'Film Analysis', 'Watch Parties', 'Movie Lists',
                    'TV Lists', 'Documentaries', 'Animation', 'Short Films', 'International Cinema',
                    'Classic Cinema', 'Film Festivals', 'Cinema News', 'Spoiler-free Reviews', 'Viewing History'
                ]],
            ],
            '🧰 Productivity & Work' => [
                ['name' => 'Productivity Tools', 'icon' => 'tools', 'color' => '#06b6d4', 'tags' => [
                    'Task Management', 'Note Taking', 'Time Management', 'Calendar Planning', 'Project Planning',
                    'Goal Tracking', 'Habit Tracking', 'Reminders', 'Checklists', 'Personal Knowledge Management',
                    'Focus Sessions', 'Time Blocking', 'Daily Planning', 'Weekly Planning', 'Meeting Notes',
                    'Templates', 'Automation', 'Integrations', 'Notifications', 'Dashboards',
                    'Search', 'Collaboration', 'Workflows', 'Recurrence', 'Prioritization',
                    'Progress Tracking', 'Productivity Analytics', 'Personal Organization', 'Digital Filing', 'Inbox Management'
                ]],
                ['name' => 'Project Management', 'icon' => 'list-check', 'color' => '#06b6d4', 'tags' => [
                    'Projects', 'Tasks', 'Subtasks', 'Milestones', 'Dependencies',
                    'Kanban', 'Gantt Charts', 'Roadmaps', 'Sprints', 'Backlogs',
                    'Agile', 'Scrum', 'Kanban Method', 'Project Templates', 'Resource Planning',
                    'Time Tracking', 'Workload Management', 'Project Risks', 'Issue Tracking', 'Project Reports',
                    'Team Collaboration', 'Approvals', 'Documents', 'Project Dashboards', 'Status Updates',
                    'Deadlines', 'Priorities', 'Project Planning', 'Project Archives', 'Project Retrospectives'
                ]],
                ['name' => 'Note Taking & Knowledge', 'icon' => 'notebook', 'color' => '#06b6d4', 'tags' => [
                    'Notes', 'Knowledge Bases', 'Wikis', 'Backlinking', 'Tags',
                    'Folders', 'Databases', 'Daily Notes', 'Meeting Notes', 'Research Notes',
                    'Bookmarks', 'Clipping', 'Markdown', 'Rich Text', 'Graph Views',
                    'Templates', 'Search', 'Full-text Search', 'Version History', 'Collaboration',
                    'Permissions', 'Offline Access', 'Synchronization', 'File Attachments', 'Embeds',
                    'Web Capture', 'Knowledge Graphs', 'Personal Knowledge Management', 'Second Brain', 'Information Organization'
                ]],
                ['name' => 'Calendar & Scheduling', 'icon' => 'calendar', 'color' => '#06b6d4', 'tags' => [
                    'Calendars', 'Events', 'Appointments', 'Meetings', 'Recurring Events',
                    'Time Zones', 'Availability', 'Scheduling Links', 'Reminders', 'Notifications',
                    'Invitations', 'Shared Calendars', 'Resource Booking', 'Room Booking', 'Agenda Views',
                    'Day Views', 'Week Views', 'Month Views', 'Year Views', 'Time Blocking',
                    'Scheduling Automation', 'Meeting Buffers', 'Focus Time', 'Working Hours', 'Travel Time',
                    'Event Templates', 'Calendar Sync', 'Conflict Detection', 'Meeting Coordination', 'Scheduling Analytics'
                ]],
                ['name' => 'Communication & Collaboration', 'icon' => 'messages', 'color' => '#06b6d4', 'tags' => [
                    'Team Chat', 'Direct Messaging', 'Channels', 'Threads', 'Mentions',
                    'File Sharing', 'Screen Sharing', 'Video Meetings', 'Voice Calls', 'Meeting Notes',
                    'Announcements', 'Notifications', 'Search', 'Message History', 'Reactions',
                    'Polls', 'Task Collaboration', 'Document Collaboration', 'Comments', 'Approvals',
                    'Guest Access', 'Permissions', 'Team Spaces', 'External Collaboration', 'Integrations',
                    'Automation', 'Presence', 'Status Updates', 'Communication Workflows', 'Moderation'
                ]],
                ['name' => 'File & Document Management', 'icon' => 'folder-open', 'color' => '#06b6d4', 'tags' => [
                    'File Storage', 'Cloud Storage', 'Document Management', 'Folders', 'Collections',
                    'Search', 'Full-text Search', 'File Preview', 'File Sharing', 'Permissions',
                    'Version History', 'Comments', 'Annotations', 'Document Collaboration', 'File Requests',
                    'Uploads', 'Downloads', 'Synchronization', 'Offline Access', 'File Organization',
                    'Metadata', 'OCR', 'PDF Management', 'Document Conversion', 'Templates',
                    'Archives', 'Backups', 'Retention', 'Access Control', 'Document Sharing'
                ]],
                ['name' => 'Automation & Workflow', 'icon' => 'automation', 'color' => '#06b6d4', 'tags' => [
                    'Workflow Automation', 'Trigger-based Automation', 'Scheduled Automation', 'Event-driven Automation', 'Webhooks',
                    'Integrations', 'API Automation', 'Data Sync', 'Notifications', 'Approvals',
                    'Task Automation', 'Form Automation', 'Email Automation', 'File Automation', 'Spreadsheet Automation',
                    'CRM Automation', 'Marketing Automation', 'Reporting Automation', 'Content Automation', 'AI Automation',
                    'Conditional Logic', 'Loops', 'Filters', 'Transformations', 'Error Handling',
                    'Retries', 'Logging', 'Workflow Monitoring', 'Reusable Workflows', 'Automation Templates'
                ]],
            ],
            '💰 Finance & Economics' => [
                ['name' => 'Personal Finance', 'icon' => 'wallet', 'color' => '#8b5cf6', 'tags' => [
                    'Budgeting', 'Expense Tracking', 'Income Tracking', 'Cash Flow', 'Net Worth',
                    'Savings', 'Financial Goals', 'Debt Tracking', 'Bill Tracking', 'Subscriptions',
                    'Recurring Expenses', 'Bank Accounts', 'Cash Accounts', 'Category Management', 'Spending Analysis',
                    'Financial Reports', 'Budget Alerts', 'Transaction Imports', 'Receipt Tracking', 'Shared Finances',
                    'Household Finance', 'Emergency Fund', 'Sinking Funds', 'Expense Forecasting', 'Income Forecasting',
                    'Financial Planning', 'Monthly Reviews', 'Financial Habits', 'Money Management', 'Privacy Controls'
                ]],
                ['name' => 'Accounting', 'icon' => 'calculator', 'color' => '#8b5cf6', 'tags' => [
                    'Bookkeeping', 'General Ledger', 'Accounts Payable', 'Accounts Receivable', 'Invoices',
                    'Bills', 'Expenses', 'Revenue', 'Cash Flow', 'Bank Reconciliation',
                    'Financial Statements', 'Balance Sheet', 'Income Statement', 'Trial Balance', 'Journal Entries',
                    'Tax Reporting', 'Payroll', 'Fixed Assets', 'Depreciation', 'Inventory Accounting',
                    'Cost Accounting', 'Budgeting', 'Forecasting', 'Audit Trails', 'Accounting Controls',
                    'Multi-currency', 'Recurring Transactions', 'Financial Closing', 'Chart of Accounts', 'Accounting Reports'
                ]],
                ['name' => 'Investing & Markets', 'icon' => 'chart-candle', 'color' => '#8b5cf6', 'tags' => [
                    'Stocks', 'Bonds', 'ETFs', 'Mutual Funds', 'Index Funds',
                    'Commodities', 'Currencies', 'Asset Allocation', 'Portfolio Management', 'Diversification',
                    'Risk Management', 'Market Research', 'Fundamental Analysis', 'Technical Analysis', 'Valuation',
                    'Financial Ratios', 'Performance Tracking', 'Benchmarking', 'Rebalancing', 'Dividend Tracking',
                    'Income Investing', 'Growth Investing', 'Value Investing', 'Long-term Investing', 'Market News',
                    'Economic Indicators', 'Price Alerts', 'Watchlists', 'Paper Trading', 'Portfolio Analytics'
                ]],
                ['name' => 'Economics', 'icon' => 'building-bank', 'color' => '#8b5cf6', 'tags' => [
                    'Macroeconomics', 'Microeconomics', 'Inflation', 'Interest Rates', 'GDP',
                    'Employment', 'Unemployment', 'Fiscal Policy', 'Monetary Policy', 'Trade',
                    'International Economics', 'Economic Growth', 'Business Cycles', 'Consumer Confidence', 'Productivity',
                    'Public Finance', 'Market Structures', 'Supply and Demand', 'Elasticity', 'Game Theory',
                    'Econometrics', 'Economic Data', 'Economic Forecasting', 'Development Economics', 'Behavioral Economics',
                    'Labor Economics', 'Environmental Economics', 'Financial Economics', 'Economic History', 'Policy Analysis'
                ]],
            ],
            '🔐 Security & Privacy' => [
                ['name' => 'Cybersecurity', 'icon' => 'shield-lock', 'color' => '#14b8a6', 'tags' => [
                    'Network Security', 'Application Security', 'Web Security', 'Cloud Security', 'Endpoint Security',
                    'Identity Security', 'Access Control', 'Authentication', 'Authorization', 'Multi-factor Authentication',
                    'Encryption', 'Key Management', 'Secrets Management', 'Vulnerability Management', 'Penetration Testing',
                    'Threat Modeling', 'Security Testing', 'Security Monitoring', 'Incident Response', 'Security Auditing',
                    'Security Policies', 'Security Awareness', 'Phishing Defense', 'Malware Analysis', 'Secure Coding',
                    'DevSecOps', 'Zero Trust', 'Security Architecture', 'Risk Assessment', 'Security Compliance'
                ]],
                ['name' => 'Privacy & Data Protection', 'icon' => 'lock', 'color' => '#14b8a6', 'tags' => [
                    'Data Privacy', 'Privacy Policies', 'Consent Management', 'Data Minimization', 'Data Retention',
                    'Data Deletion', 'Data Access Requests', 'Anonymization', 'Pseudonymization', 'Encryption',
                    'Privacy by Design', 'Cookie Management', 'Tracking Prevention', 'Fingerprinting Protection', 'Third-party Data',
                    'Data Sharing', 'Privacy Audits', 'Data Governance', 'Data Classification', 'Access Controls',
                    'Identity Management', 'Secure Storage', 'Privacy Compliance', 'User Controls', 'Transparency',
                    'Data Portability', 'Privacy Risk', 'Children’s Privacy', 'Sensitive Data', 'Privacy Engineering'
                ]],
                ['name' => 'Networking', 'icon' => 'network', 'color' => '#14b8a6', 'tags' => [
                    'TCP/IP', 'HTTP', 'DNS', 'DHCP', 'Routing',
                    'Switching', 'Firewalls', 'VPN', 'Proxy Servers', 'Load Balancing',
                    'CDN', 'NAT', 'IPv4', 'IPv6', 'Subnets',
                    'Network Monitoring', 'Packet Analysis', 'Wireless Networking', 'Wi-Fi', 'Network Security',
                    'TLS', 'Certificates', 'Reverse Proxies', 'Service Discovery', 'Network Automation',
                    'SDN', 'Containers Networking', 'Cloud Networking', 'Troubleshooting', 'Network Performance'
                ]],
                ['name' => 'Operating Systems', 'icon' => 'device-desktop', 'color' => '#14b8a6', 'tags' => [
                    'Linux', 'Windows', 'macOS', 'File Systems', 'Processes',
                    'Threads', 'Memory Management', 'Permissions', 'Users and Groups', 'Shells',
                    'Command Line', 'Package Management', 'System Services', 'System Logs', 'Scheduling',
                    'Drivers', 'Networking', 'Virtualization', 'Containers', 'System Security',
                    'Backups', 'Disk Management', 'Performance Monitoring', 'Process Management', 'Configuration',
                    'System Administration', 'Automation', 'Remote Access', 'System Recovery', 'Troubleshooting'
                ]],
            ],
            '🌍 Travel & Lifestyle' => [
                ['name' => 'Travel Planning', 'icon' => 'plane', 'color' => '#ef4444', 'tags' => [
                    'Trip Planning', 'Itineraries', 'Destinations', 'Flights', 'Hotels',
                    'Hostels', 'Vacation Rentals', 'Transportation', 'Car Rentals', 'Public Transit',
                    'Travel Budgets', 'Packing Lists', 'Travel Checklists', 'Trip Research', 'Attractions',
                    'Museums', 'Parks', 'Beaches', 'Hiking', 'Food Experiences',
                    'Local Culture', 'Travel Safety', 'Travel Insurance', 'Visa Information', 'Currency Conversion',
                    'Maps', 'Navigation', 'Weather Planning', 'Travel Photography', 'Travel Journaling'
                ]],
                ['name' => 'Food & Cooking', 'icon' => 'tools-kitchen-2', 'color' => '#ef4444', 'tags' => [
                    'Recipes', 'Meal Planning', 'Baking', 'Desserts', 'Vegetarian',
                    'Vegan', 'High-protein Meals', 'Healthy Cooking', 'Quick Meals', 'Budget Meals',
                    'Breakfast', 'Lunch', 'Dinner', 'Snacks', 'Drinks',
                    'Sauces', 'Soups', 'Salads', 'Pasta', 'Rice Dishes',
                    'Bread', 'Cakes', 'Cookies', 'Grilling', 'Roasting',
                    'Frying', 'Air Frying', 'Kitchen Techniques', 'Ingredient Substitutions', 'Food Preparation'
                ]],
                ['name' => 'Home & Lifestyle', 'icon' => 'home', 'color' => '#ef4444', 'tags' => [
                    'Home Organization', 'Cleaning', 'Decluttering', 'Interior Design', 'Home Decor',
                    'Furniture Planning', 'Room Layouts', 'DIY Projects', 'Gardening', 'Plant Care',
                    'Home Maintenance', 'Storage Solutions', 'Smart Home', 'Energy Saving', 'Home Office',
                    'Lighting', 'Color Schemes', 'Minimalism', 'Sustainability', 'Home Budgeting',
                    'Household Planning', 'Meal Planning', 'Chores', 'Routines', 'Family Organization',
                    'Pet Care', 'Laundry', 'Home Safety', 'Moving', 'Home Improvement'
                ]],
                ['name' => 'Shopping & Consumer Tools', 'icon' => 'shopping-bag', 'color' => '#ef4444', 'tags' => [
                    'Price Comparison', 'Product Discovery', 'Product Reviews', 'Deals', 'Coupons',
                    'Wishlists', 'Shopping Lists', 'Product Tracking', 'Price Alerts', 'Specifications',
                    'Product Research', 'Brand Research', 'Category Browsing', 'Marketplace Search', 'Secondhand Shopping',
                    'Local Shopping', 'Subscriptions', 'Gift Ideas', 'Size Guides', 'Buying Guides',
                    'Return Policies', 'Shipping Information', 'Availability Tracking', 'Stock Alerts', 'Product Alternatives',
                    'Sustainability', 'Warranty Information', 'Consumer Rights', 'Purchase History', 'Shopping Analytics'
                ]],
            ],
            '📰 Media & Information' => [
                ['name' => 'News & Current Events', 'icon' => 'news', 'color' => '#0ea5e9', 'tags' => [
                    'Breaking News', 'World News', 'Local News', 'Business News', 'Technology News',
                    'Science News', 'Culture News', 'Sports News', 'Politics News', 'Investigative Journalism',
                    'Opinion', 'Editorials', 'Newsletters', 'News Aggregation', 'Topic Feeds',
                    'News Search', 'Fact Checking', 'Media Literacy', 'Source Comparison', 'News Archives',
                    'Press Releases', 'Local Reporting', 'Photojournalism', 'Data Journalism', 'Explainers',
                    'News Analysis', 'Podcasts', 'Video News', 'Morning Briefings', 'Daily Digests'
                ]],
                ['name' => 'Books & Literature', 'icon' => 'books', 'color' => '#0ea5e9', 'tags' => [
                    'Book Discovery', 'Book Reviews', 'Reading Lists', 'Reading Tracking', 'Genres',
                    'Fiction', 'Nonfiction', 'Poetry', 'Essays', 'Short Stories',
                    'Comics', 'Graphic Novels', 'Classics', 'Contemporary Literature', 'Young Adult',
                    'Children’s Literature', 'Audiobooks', 'Ebooks', 'Book Clubs', 'Author Discovery',
                    'Literary Analysis', 'Book Summaries', 'Reading Challenges', 'Library Resources', 'Publishing',
                    'Writing Communities', 'Quotes and Notes', 'Recommendations', 'Reading Statistics', 'Literary Events'
                ]],
                ['name' => 'Reference & Knowledge', 'icon' => 'bookmarks', 'color' => '#0ea5e9', 'tags' => [
                    'Encyclopedias', 'Dictionaries', 'Thesauruses', 'Glossaries', 'Terminology',
                    'Definitions', 'Reference Guides', 'How-to Guides', 'Manuals', 'Documentation',
                    'Specifications', 'Standards', 'Maps', 'Timelines', 'Biographies',
                    'Historical References', 'Statistical References', 'Fact Sheets', 'Datasets', 'Knowledge Bases',
                    'Search Tools', 'Research Portals', 'Archives', 'Indexes', 'Catalogs',
                    'Directories', 'Cross-references', 'Citation Resources', 'Open Knowledge', 'Educational References'
                ]],
                ['name' => 'Maps & Geography', 'icon' => 'map', 'color' => '#0ea5e9', 'tags' => [
                    'Interactive Maps', 'Navigation', 'Geocoding', 'Reverse Geocoding', 'Route Planning',
                    'Directions', 'Public Transit Maps', 'Traffic Maps', 'Satellite Imagery', 'Street Imagery',
                    'Terrain Maps', 'Weather Maps', 'Historical Maps', 'Topographic Maps', 'Political Maps',
                    'Population Maps', 'Demographic Maps', 'GIS', 'Spatial Data', 'Geospatial Analysis',
                    'Location Search', 'Place Discovery', 'Distance Calculation', 'Area Measurement', 'Coordinates',
                    'GeoJSON', 'Map Layers', 'Mapping Tools', 'Cartography', 'Location Visualization'
                ]],
            ],
            '🏗️ Engineering & Specialized Tools' => [
                ['name' => 'Engineering', 'icon' => 'ruler-2', 'color' => '#84cc16', 'tags' => [
                    'Mechanical Engineering', 'Electrical Engineering', 'Civil Engineering', 'Chemical Engineering', 'Industrial Engineering',
                    'Manufacturing Engineering', 'Systems Engineering', 'Control Systems', 'Robotics', 'CAD',
                    'CAM', 'CAE', 'Simulation', 'Finite Element Analysis', 'Computational Fluid Dynamics',
                    'Circuit Design', 'PCB Design', '3D Printing', 'Prototyping', 'Technical Drawings',
                    'Materials', 'Thermodynamics', 'Fluid Mechanics', 'Mechanics', 'Electronics',
                    'Automation', 'Instrumentation', 'Engineering Calculations', 'Technical Standards', 'Engineering Documentation'
                ]],
                ['name' => 'Architecture & Construction', 'icon' => 'building', 'color' => '#84cc16', 'tags' => [
                    'Architectural Design', 'Floor Plans', 'Site Planning', 'Building Information Modeling', '3D Visualization',
                    'Rendering', 'Interior Architecture', 'Landscape Architecture', 'Urban Design', 'Construction Documents',
                    'Technical Drawings', 'Structural Design', 'Material Selection', 'Lighting Design', 'Space Planning',
                    'Accessibility', 'Building Codes', 'Sustainability', 'Energy Modeling', 'Cost Estimation',
                    'Construction Planning', 'Project Scheduling', 'Quantity Takeoff', 'Site Management', 'Contractors',
                    'Building Maintenance', 'Renovation', 'Property Development', 'Architecture Research', 'Design Visualization'
                ]],
                ['name' => 'Robotics & Hardware', 'icon' => 'robot', 'color' => '#84cc16', 'tags' => [
                    'Microcontrollers', 'Embedded Systems', 'Sensors', 'Actuators', 'Motor Control',
                    'Robotics Programming', 'Computer Vision', 'Navigation', 'SLAM', 'Control Systems',
                    'Embedded Linux', 'IoT Devices', 'Wireless Communication', 'Bluetooth', 'Wi-Fi',
                    'Serial Communication', 'GPIO', 'PWM', 'Robotics Simulation', 'Robot Kinematics',
                    'Robot Dynamics', 'Path Planning', 'Motion Planning', 'Autonomous Systems', 'Human-robot Interaction',
                    'Prototyping', 'PCB Design', '3D Printing', 'Hardware Debugging', 'Firmware'
                ]],
                ['name' => 'Data Visualization', 'icon' => 'chart-infographic', 'color' => '#84cc16', 'tags' => [
                    'Charts', 'Graphs', 'Dashboards', 'Interactive Visualization', 'Statistical Graphics',
                    'Time Series Visualization', 'Geospatial Visualization', 'Network Visualization', 'Hierarchical Visualization', 'Flow Diagrams',
                    'Scatter Plots', 'Bar Charts', 'Line Charts', 'Area Charts', 'Pie Charts',
                    'Heatmaps', 'Histograms', 'Box Plots', 'Bubble Charts', 'Sankey Diagrams',
                    'Treemaps', 'Timelines', 'Maps', 'Annotation', 'Color Scales',
                    'Storytelling with Data', 'Data Exploration', 'Visual Analytics', 'Report Design', 'Accessibility'
                ]],
            ],
        ];

        foreach ($groupedCategories as $groupName => $categories) {
            // 1. Buat induk Category Group
            $group = CategoryGroup::firstOrCreate(['name' => $groupName]);

            // 2. Buat Category
            foreach ($categories as $catData) {
                $category = Category::firstOrCreate(
                    ['slug' => Str::slug($catData['name'])],
                    [
                        'category_group_id' => $group->id,
                        'name' => $catData['name'],
                        'icon' => $catData['icon'],
                        'color' => $catData['color']
                    ]
                );

                // 3. Buat tags spesifik untuk Category ini
                foreach ($catData['tags'] as $tagName) {
                    Tag::firstOrCreate(
                        [
                            'category_id' => $category->id,
                            'slug' => Str::slug($tagName)
                        ],
                        [
                            'name' => $tagName,
                            'status' => 'approved'
                        ]
                    );
                }
            }
        }
    }
}