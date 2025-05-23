// Dictionnaire de traduction pour toute la plateforme
const translations = {
  fr: {
    // Common elements
    "back_to_dashboard": "Tableau de bord",
    "settings": "Paramètres",
    "region": "Région de Béchar - Algérie",
    "save": "Enregistrer",
    "cancel": "Annuler",
    "delete": "Supprimer",
    "edit": "Modifier",
    "add": "Ajouter",
    "search": "Rechercher",
    "profile": "Mon Profil",
    "logout": "Déconnexion",
    "dashboard": "Tableau de Bord",
    "planning": "Planification",
    "reports": "Rapports",
    "pests": "Ravageurs",
    "crops": "Cultures",
    "alerts": "Alertes",
    "guide": "Guide Béchar",

    // Landing page
    "platform_name": "AgriTech Béchar",
    "home": "Accueil",
    "features": "Fonctionnalités",
    "about": "À propos",
    "contact": "Contact",
    "login": "Connexion",
    "hero_title": "Gestion Agricole Intelligente",
    "hero_subtitle": "pour la région de Béchar",
    "hero_description": "Une plateforme innovante qui combine données météorologiques précises, planification intelligente et gestion optimisée des ressources pour l'agriculture en zone aride.",
    "get_started": "Commencer",
    "learn_more": "En savoir plus",
    "features_title": "Fonctionnalités Principales",
    "features_subtitle": "Découvrez comment notre plateforme révolutionne l'agriculture en zone aride",
    "weather_feature": "Données Météo Précises",
    "weather_description": "Accédez à des prévisions météorologiques précises et adaptées à votre zone agricole spécifique.",
    "planning_feature": "Planification Intelligente",
    "planning_description": "Planifiez vos activités agricoles en fonction des conditions météorologiques et des besoins de vos cultures.",
    "irrigation_feature": "Gestion de l'Irrigation",
    "irrigation_description": "Optimisez votre consommation d'eau grâce à des systèmes d'irrigation intelligents et adaptés.",
    "analytics_feature": "Analyses et Statistiques",
    "analytics_description": "Suivez vos performances agricoles avec des tableaux de bord et des rapports détaillés.",
    "collaboration_feature": "Collaboration d'Équipe",
    "collaboration_description": "Travaillez efficacement avec votre équipe grâce à des outils de gestion des utilisateurs et des rôles.",
    "mobile_feature": "Accès Mobile",
    "mobile_description": "Accédez à votre plateforme depuis n'importe où, sur tous vos appareils.",
    "showcase_title": "Technologie de Pointe",
    "showcase_description": "Notre plateforme utilise des technologies avancées pour vous offrir une expérience utilisateur exceptionnelle et des fonctionnalités puissantes adaptées à l'agriculture en zone aride.",
    "showcase_item1": "Interface intuitive et moderne",
    "showcase_item2": "Visualisation 3D des données",
    "showcase_item3": "Algorithmes d'optimisation avancés",
    "showcase_item4": "Support multilingue (Français, Anglais, Arabe)",
    "parallax_title": "Optimisez votre Agriculture en Zone Aride",
    "parallax_description": "Rejoignez des centaines d'agriculteurs qui ont déjà transformé leur approche grâce à notre plateforme innovante.",
    "start_now": "Commencer maintenant",
    "about_title": "À Propos de Notre Plateforme",
    "about_description1": "AgriTech Béchar est née de la volonté de soutenir les agriculteurs de la région de Béchar face aux défis spécifiques de l'agriculture en zone aride.",
    "about_description2": "Notre équipe d'experts en agronomie, météorologie et développement logiciel a créé une solution complète qui répond aux besoins uniques de cette région.",
    "about_description3": "Nous nous engageons à améliorer continuellement notre plateforme pour vous offrir les meilleurs outils et vous aider à prospérer malgré les conditions climatiques difficiles.",
    "testimonials_title": "Ce que disent nos utilisateurs",
    "testimonials_subtitle": "Découvrez les expériences des agriculteurs qui utilisent notre plateforme",
    "testimonial1_text": "\"Grâce à AgriTech Béchar, j'ai pu réduire ma consommation d'eau de 30% tout en augmentant mon rendement. C'est une révolution pour mon exploitation de palmiers dattiers.\"",
    "testimonial1_role": "Agriculteur à Béchar",
    "testimonial2_text": "\"La planification intelligente m'a permis d'optimiser mes activités agricoles en fonction des prévisions météo. Je gagne un temps précieux et mes cultures sont mieux protégées.\"",
    "testimonial2_role": "Coopérative agricole",
    "testimonial3_text": "\"L'interface multilingue et les analyses détaillées ont transformé notre façon de gérer notre exploitation. Nous prenons maintenant des décisions basées sur des données précises.\"",
    "testimonial3_role": "Agronome",
    "contact_title": "Contactez-nous",
    "contact_description": "Vous avez des questions ou besoin d'assistance ? N'hésitez pas à nous contacter.",
    "contact_address": "Béchar, Algérie",
    "contact_name": "Nom complet",
    "contact_email": "Email",
    "contact_subject": "Sujet",
    "contact_message": "Message",
    "contact_send": "Envoyer le message",
    "footer_description": "Une solution innovante pour l'agriculture en zone aride, combinant données météorologiques précises et outils de planification intelligents.",
    "footer_links": "Liens",
    "footer_features": "Fonctionnalités",
    "footer_newsletter": "Newsletter",
    "footer_newsletter_text": "Abonnez-vous pour recevoir les dernières actualités et mises à jour.",
    "footer_language": "Langue:",
    "footer_rights": "Tous droits réservés.",

    // Dashboard
    "dashboard_title": "Tableau de Bord Agricole",
    "overview": "Vue d'ensemble",
    "pivots": "Pivots",
    "real_time_sensors": "Capteurs en Temps Réel",
    "soil_temp": "Température sol",
    "soil_humidity": "Humidité sol",
    "air_humidity": "Humidité air",
    "luminosity": "Luminosité",
    "agricultural_info": "Informations Agricoles",
    "crops_info": "Cultures:",
    "irrigation_info": "Irrigation:",
    "season_info": "Saison:",
    "yield_info": "Rendement:",
    "weather_title": "Météo - Béchar",
    "wind": "Vent:",
    "humidity": "Humidité:",
    "max_temp": "Max:",
    "min_temp": "Min:",
    "forecast_title": "Prévisions sur 5 jours",
    "agricultural_zones": "Carte des Zones Agricoles",
    "cultivated_areas": "Zones cultivées",
    "water_consumption": "Consommation d'eau",
    "average_yield": "Rendement moyen",
    "completed_tasks": "Tâches complétées",
    "irrigation_pivots": "Pivots d'Irrigation",
    "add_pivot": "Ajouter",
    "delete_pivot": "Supprimer",
    "plan_pivot": "Planifier",

    // Settings page
    "language_settings": "Paramètres de Langue",
    "language_description": "Sélectionnez la langue que vous souhaitez utiliser pour la plateforme.",
    "weather_config": "Configuration Météo",
    "weather_provider": "Fournisseur de données météo",
    "select_provider": "Sélectionner un fournisseur",
    "provider_help": "Choisissez le service météo que vous souhaitez utiliser pour les données.",
    "api_key": "Clé API",
    "api_key_help": "Vous pouvez obtenir une clé API en vous inscrivant sur le site du fournisseur sélectionné.",
    "about": "À propos",
    "about_description": "Cette plateforme de gestion agricole est conçue pour aider les agriculteurs de la région de Béchar à optimiser leurs activités grâce à des données météorologiques précises et des outils de planification.",
    "version": "Version:",
    "last_update": "Dernière mise à jour:",
    "weather_settings_saved": "Paramètres météo enregistrés avec succès!",
    "language_settings_saved": "Langue changée avec succès!",

    // User management
    "user_management": "Gestion des Utilisateurs",
    "user_list": "Liste des Utilisateurs",
    "add_user": "Ajouter Utilisateur",
    "edit_user": "Modifier Utilisateur",
    "delete_user": "Supprimer Utilisateur",
    "user_id": "ID",
    "user_status": "Statut",
    "user_name": "Nom",
    "user_email": "Email",
    "user_role": "Rôle",
    "user_actions": "Actions",
    "online": "En ligne",
    "offline": "Hors ligne",
    "admin": "Administrateur",
    "user": "Utilisateur",
    "full_name": "Nom Complet",
    "password": "Mot de passe",
    "confirm_password": "Confirmer le mot de passe",
    "search_users": "Rechercher par nom ou email...",
    "all_roles": "Tous les rôles",
    "all_statuses": "Tous les statuts",
    "no_users_found": "Aucun utilisateur trouvé",
    "delete_confirmation": "Êtes-vous sûr?",
    "delete_warning": "Cette action ne peut pas être annulée!",
    "confirm_delete": "Oui, supprimer!",
    "user_added": "Utilisateur ajouté avec succès!",
    "user_updated": "Utilisateur mis à jour avec succès!",
    "user_deleted": "Utilisateur supprimé avec succès!",

    // Profile page
    "personal_info": "Informations Personnelles",
    "phone": "Téléphone",
    "location": "Localisation",
    "bio": "Bio",
    "security": "Sécurité",
    "current_password": "Mot de passe actuel",
    "new_password": "Nouveau mot de passe",
    "update": "Mettre à jour",
    "profile_updated": "Profil mis à jour avec succès!",
    "password_updated": "Mot de passe mis à jour avec succès!",
    "recent_activity": "Activité Récente",
    "no_activity": "Aucune activité récente",

    // Planning page
    "activity_calendar": "Calendrier des Activités",
    "pivot_planning": "Planification des Pivots",
    "stats": "Statistiques",
    "tasks_month": "Tâches ce mois",
    "date": "Date",
    "pivot": "Pivot",
    "time": "Heure",
    "activity": "Activité",
    "category": "Catégorie",
    "actions": "Actions",
    "new_planning": "Nouvelle Planification",
    "irrigation": "Irrigation",
    "fertilization": "Fertilisation",
    "harvest": "Récolte",
    "treatment": "Traitement",
    "other": "Autre",
    "select_pivots": "Sélectionner les pivots",
    "fill_all_fields": "Veuillez remplir tous les champs obligatoires et sélectionner au moins un pivot",
    "time_error": "L'heure de fin doit être après l'heure de début",
    "planning_saved": "Planification enregistrée pour {count} pivot(s)!",
    "delete_planning_confirm": "Voulez-vous vraiment supprimer cette planification?",
    "yes_delete": "Oui, supprimer!",
    "planning_deleted": "Planification supprimée avec succès!",
    "delete_all_confirm": "Êtes-vous sûr de vouloir supprimer TOUTES les planifications?",
    "irreversible_action": "Cette action est irréversible!",
    "yes_delete_all": "Oui, tout supprimer!",
    "all_plannings_deleted": "Toutes les planifications ont été supprimées!",

    // History page
    "history": "Historique",
    "task_history": "Historique des Tâches",
    "completed_tasks": "Tâches Complétées",
    "time_period": "Période",
    "all_time": "Toutes les périodes",
    "by_week": "Par semaine",
    "by_month": "Par mois",
    "all_pivots": "Tous les pivots",
    "all_categories": "Toutes les catégories",
    "no_completed_tasks": "Aucune tâche complétée trouvée",
    "tasks_appear_here": "Les tâches complétées apparaîtront ici",
    "filters": "Filtres",
    "type": "Type",
    "all": "Tous",
    "tasks": "Tâches",
    "date_range": "Période",
    "today": "Aujourd'hui",
    "this_week": "Cette semaine",
    "this_month": "Ce mois"
  },
  en: {
    // Common elements
    "back_to_dashboard": "Dashboard",
    "settings": "Settings",
    "region": "Bechar Region - Algeria",
    "save": "Save",
    "cancel": "Cancel",
    "delete": "Delete",
    "edit": "Edit",
    "add": "Add",
    "search": "Search",
    "profile": "My Profile",
    "logout": "Logout",
    "dashboard": "Dashboard",
    "planning": "Planning",
    "reports": "Reports",
    "pests": "Pests",
    "crops": "Crops",
    "alerts": "Alerts",
    "guide": "Bechar Guide",

    // Landing page
    "platform_name": "AgriTech Bechar",
    "home": "Home",
    "features": "Features",
    "about": "About",
    "contact": "Contact",
    "login": "Login",
    "hero_title": "Smart Agricultural Management",
    "hero_subtitle": "for the Bechar region",
    "hero_description": "An innovative platform that combines accurate weather data, smart planning, and optimized resource management for arid zone agriculture.",
    "get_started": "Get Started",
    "learn_more": "Learn More",
    "features_title": "Key Features",
    "features_subtitle": "Discover how our platform revolutionizes agriculture in arid zones",
    "weather_feature": "Accurate Weather Data",
    "weather_description": "Access precise weather forecasts tailored to your specific agricultural area.",
    "planning_feature": "Intelligent Planning",
    "planning_description": "Plan your agricultural activities based on weather conditions and crop needs.",
    "irrigation_feature": "Irrigation Management",
    "irrigation_description": "Optimize your water consumption with smart and adapted irrigation systems.",
    "analytics_feature": "Analytics & Statistics",
    "analytics_description": "Track your agricultural performance with detailed dashboards and reports.",
    "collaboration_feature": "Team Collaboration",
    "collaboration_description": "Work efficiently with your team through user and role management tools.",
    "mobile_feature": "Mobile Access",
    "mobile_description": "Access your platform from anywhere, on all your devices.",
    "showcase_title": "Cutting-Edge Technology",
    "showcase_description": "Our platform uses advanced technologies to provide you with an exceptional user experience and powerful features adapted to arid zone agriculture.",
    "showcase_item1": "Intuitive and modern interface",
    "showcase_item2": "3D data visualization",
    "showcase_item3": "Advanced optimization algorithms",
    "showcase_item4": "Multilingual support (French, English, Arabic)",
    "parallax_title": "Optimize Your Arid Zone Agriculture",
    "parallax_description": "Join hundreds of farmers who have already transformed their approach with our innovative platform.",
    "start_now": "Start Now",
    "about_title": "About Our Platform",
    "about_description1": "AgriTech Bechar was born from the desire to support farmers in the Bechar region facing the specific challenges of arid zone agriculture.",
    "about_description2": "Our team of experts in agronomy, meteorology, and software development has created a comprehensive solution that meets the unique needs of this region.",
    "about_description3": "We are committed to continuously improving our platform to provide you with the best tools and help you thrive despite challenging climate conditions.",
    "testimonials_title": "What Our Users Say",
    "testimonials_subtitle": "Discover the experiences of farmers using our platform",
    "testimonial1_text": "\"Thanks to AgriTech Bechar, I was able to reduce my water consumption by 30% while increasing my yield. It's a revolution for my date palm farm.\"",
    "testimonial1_role": "Farmer in Bechar",
    "testimonial2_text": "\"Smart planning has allowed me to optimize my agricultural activities based on weather forecasts. I save valuable time and my crops are better protected.\"",
    "testimonial2_role": "Agricultural cooperative",
    "testimonial3_text": "\"The multilingual interface and detailed analytics have transformed how we manage our farm. We now make decisions based on accurate data.\"",
    "testimonial3_role": "Agronomist",
    "contact_title": "Contact Us",
    "contact_description": "Have questions or need assistance? Don't hesitate to contact us.",
    "contact_address": "Bechar, Algeria",
    "contact_name": "Full Name",
    "contact_email": "Email",
    "contact_subject": "Subject",
    "contact_message": "Message",
    "contact_send": "Send Message",
    "footer_description": "An innovative solution for arid zone agriculture, combining accurate weather data and smart planning tools.",
    "footer_links": "Links",
    "footer_features": "Features",
    "footer_newsletter": "Newsletter",
    "footer_newsletter_text": "Subscribe to receive the latest news and updates.",
    "footer_language": "Language:",
    "footer_rights": "All rights reserved.",

    // Dashboard
    "dashboard_title": "Agricultural Dashboard",
    "overview": "Overview",
    "pivots": "Pivots",
    "real_time_sensors": "Real-Time Sensors",
    "soil_temp": "Soil temperature",
    "soil_humidity": "Soil humidity",
    "air_humidity": "Air humidity",
    "luminosity": "Luminosity",
    "agricultural_info": "Agricultural Information",
    "crops_info": "Crops:",
    "irrigation_info": "Irrigation:",
    "season_info": "Season:",
    "yield_info": "Yield:",
    "weather_title": "Weather - Bechar",
    "wind": "Wind:",
    "humidity": "Humidity:",
    "max_temp": "Max:",
    "min_temp": "Min:",
    "forecast_title": "5-Day Forecast",
    "agricultural_zones": "Agricultural Zones Map",
    "cultivated_areas": "Cultivated areas",
    "water_consumption": "Water consumption",
    "average_yield": "Average yield",
    "completed_tasks": "Completed tasks",
    "irrigation_pivots": "Irrigation Pivots",
    "add_pivot": "Add",
    "delete_pivot": "Delete",
    "plan_pivot": "Plan",

    // Settings page
    "language_settings": "Language Settings",
    "language_description": "Select the language you want to use for the platform.",
    "weather_config": "Weather Configuration",
    "weather_provider": "Weather data provider",
    "select_provider": "Select a provider",
    "provider_help": "Choose the weather service you want to use for data.",
    "api_key": "API Key",
    "api_key_help": "You can get an API key by signing up on the selected provider's website.",
    "about": "About",
    "about_description": "This agricultural management platform is designed to help farmers in the Bechar region optimize their activities through accurate weather data and planning tools.",
    "version": "Version:",
    "last_update": "Last update:",
    "weather_settings_saved": "Weather settings saved successfully!",
    "language_settings_saved": "Language changed successfully!",

    // User management
    "user_management": "User Management",
    "user_list": "User List",
    "add_user": "Add User",
    "edit_user": "Edit User",
    "delete_user": "Delete User",
    "user_id": "ID",
    "user_status": "Status",
    "user_name": "Name",
    "user_email": "Email",
    "user_role": "Role",
    "user_actions": "Actions",
    "online": "Online",
    "offline": "Offline",
    "admin": "Administrator",
    "user": "User",
    "full_name": "Full Name",
    "password": "Password",
    "confirm_password": "Confirm Password",
    "search_users": "Search by name or email...",
    "all_roles": "All roles",
    "all_statuses": "All statuses",
    "no_users_found": "No users found",
    "delete_confirmation": "Are you sure?",
    "delete_warning": "This action cannot be undone!",
    "confirm_delete": "Yes, delete it!",
    "user_added": "User added successfully!",
    "user_updated": "User updated successfully!",
    "user_deleted": "User deleted successfully!",

    // Profile page
    "personal_info": "Personal Information",
    "phone": "Phone",
    "location": "Location",
    "bio": "Bio",
    "security": "Security",
    "current_password": "Current Password",
    "new_password": "New Password",
    "update": "Update",
    "profile_updated": "Profile updated successfully!",
    "password_updated": "Password updated successfully!",
    "recent_activity": "Recent Activity",
    "no_activity": "No recent activity",

    // Planning page
    "activity_calendar": "Activity Calendar",
    "pivot_planning": "Pivot Planning",
    "stats": "Statistics",
    "tasks_month": "Tasks this month",
    "date": "Date",
    "pivot": "Pivot",
    "time": "Time",
    "activity": "Activity",
    "category": "Category",
    "actions": "Actions",
    "new_planning": "New Planning",
    "irrigation": "Irrigation",
    "fertilization": "Fertilization",
    "harvest": "Harvest",
    "treatment": "Treatment",
    "other": "Other",
    "select_pivots": "Select pivots",
    "fill_all_fields": "Please fill in all required fields and select at least one pivot",
    "time_error": "End time must be after start time",
    "planning_saved": "Planning saved for {count} pivot(s)!",
    "delete_planning_confirm": "Do you really want to delete this planning?",
    "yes_delete": "Yes, delete it!",
    "planning_deleted": "Planning deleted successfully!",
    "delete_all_confirm": "Are you sure you want to delete ALL plannings?",
    "irreversible_action": "This action is irreversible!",
    "yes_delete_all": "Yes, delete all!",
    "all_plannings_deleted": "All plannings have been deleted!",

    // History page
    "history": "History",
    "task_history": "Task History",
    "completed_tasks": "Completed Tasks",
    "time_period": "Time Period",
    "all_time": "All time periods",
    "by_week": "By week",
    "by_month": "By month",
    "all_pivots": "All pivots",
    "all_categories": "All categories",
    "no_completed_tasks": "No completed tasks found",
    "tasks_appear_here": "Completed tasks will appear here",
    "filters": "Filters",
    "type": "Type",
    "all": "All",
    "tasks": "Tasks",
    "date_range": "Date Range",
    "today": "Today",
    "this_week": "This week",
    "this_month": "This month"
  },
  ar: {
    // Common elements
    "back_to_dashboard": "لوحة التحكم",
    "settings": "الإعدادات",
    "region": "منطقة بشار - الجزائر",
    "save": "حفظ",
    "cancel": "إلغاء",
    "delete": "حذف",
    "edit": "تعديل",
    "add": "إضافة",
    "search": "بحث",
    "profile": "الملف الشخصي",
    "logout": "تسجيل الخروج",
    "dashboard": "لوحة التحكم",
    "planning": "التخطيط",
    "reports": "التقارير",
    "pests": "الآفات",
    "crops": "المحاصيل",
    "alerts": "التنبيهات",
    "guide": "دليل بشار",

    // Landing page
    "platform_name": "أجريتك بشار",
    "home": "الرئيسية",
    "features": "المميزات",
    "about": "حول",
    "contact": "اتصل بنا",
    "login": "تسجيل الدخول",
    "hero_title": "إدارة زراعية ذكية",
    "hero_subtitle": "لمنطقة بشار",
    "hero_description": "منصة مبتكرة تجمع بين بيانات الطقس الدقيقة والتخطيط الذكي وإدارة الموارد المثلى للزراعة في المناطق الجافة.",
    "get_started": "ابدأ الآن",
    "learn_more": "اعرف المزيد",
    "features_title": "المميزات الرئيسية",
    "features_subtitle": "اكتشف كيف تحدث منصتنا ثورة في الزراعة بالمناطق الجافة",
    "weather_feature": "بيانات طقس دقيقة",
    "weather_description": "الوصول إلى توقعات الطقس الدقيقة المصممة خصيصًا لمنطقتك الزراعية.",
    "planning_feature": "تخطيط ذكي",
    "planning_description": "خطط لأنشطتك الزراعية بناءً على ظروف الطقس واحتياجات المحاصيل.",
    "irrigation_feature": "إدارة الري",
    "irrigation_description": "تحسين استهلاك المياه من خلال أنظمة الري الذكية والمتكيفة.",
    "analytics_feature": "التحليلات والإحصاءات",
    "analytics_description": "تتبع أدائك الزراعي من خلال لوحات المعلومات والتقارير المفصلة.",
    "collaboration_feature": "تعاون الفريق",
    "collaboration_description": "العمل بكفاءة مع فريقك من خلال أدوات إدارة المستخدمين والأدوار.",
    "mobile_feature": "الوصول عبر الجوال",
    "mobile_description": "الوصول إلى منصتك من أي مكان، على جميع أجهزتك.",
    "showcase_title": "تكنولوجيا متطورة",
    "showcase_description": "تستخدم منصتنا تقنيات متقدمة لتوفير تجربة مستخدم استثنائية وميزات قوية مكيفة للزراعة في المناطق الجافة.",
    "showcase_item1": "واجهة بديهية وحديثة",
    "showcase_item2": "تصور ثلاثي الأبعاد للبيانات",
    "showcase_item3": "خوارزميات تحسين متقدمة",
    "showcase_item4": "دعم متعدد اللغات (الفرنسية، الإنجليزية، العربية)",
    "parallax_title": "تحسين الزراعة في المناطق الجافة",
    "parallax_description": "انضم إلى مئات المزارعين الذين غيروا بالفعل نهجهم من خلال منصتنا المبتكرة.",
    "start_now": "ابدأ الآن",
    "about_title": "حول منصتنا",
    "about_description1": "ولدت أجريتك بشار من الرغبة في دعم المزارعين في منطقة بشار الذين يواجهون تحديات محددة للزراعة في المناطق الجافة.",
    "about_description2": "قام فريقنا من الخبراء في علم الزراعة والأرصاد الجوية وتطوير البرمجيات بإنشاء حل شامل يلبي الاحتياجات الفريدة لهذه المنطقة.",
    "about_description3": "نحن ملتزمون بتحسين منصتنا باستمرار لتزويدك بأفضل الأدوات ومساعدتك على الازدهار رغم الظروف المناخية الصعبة.",
    "testimonials_title": "ماذا يقول مستخدمونا",
    "testimonials_subtitle": "اكتشف تجارب المزارعين الذين يستخدمون منصتنا",
    "testimonial1_text": "\"بفضل أجريتك بشار، تمكنت من تقليل استهلاكي للمياه بنسبة 30٪ مع زيادة المحصول. إنها ثورة لمزرعة النخيل الخاصة بي.\"",
    "testimonial1_role": "مزارع في بشار",
    "testimonial2_text": "\"سمح لي التخطيط الذكي بتحسين أنشطتي الزراعية بناءً على توقعات الطقس. أوفر وقتًا ثمينًا ومحاصيلي محمية بشكل أفضل.\"",
    "testimonial2_role": "تعاونية زراعية",
    "testimonial3_text": "\"لقد غيرت الواجهة متعددة اللغات والتحليلات المفصلة طريقة إدارتنا لمزرعتنا. نحن الآن نتخذ قرارات بناءً على بيانات دقيقة.\"",
    "testimonial3_role": "مهندس زراعي",
    "contact_title": "اتصل بنا",
    "contact_description": "هل لديك أسئلة أو تحتاج إلى مساعدة؟ لا تتردد في الاتصال بنا.",
    "contact_address": "بشار، الجزائر",
    "contact_name": "الاسم الكامل",
    "contact_email": "البريد الإلكتروني",
    "contact_subject": "الموضوع",
    "contact_message": "الرسالة",
    "contact_send": "إرسال الرسالة",
    "footer_description": "حل مبتكر للزراعة في المناطق الجافة، يجمع بين بيانات الطقس الدقيقة وأدوات التخطيط الذكية.",
    "footer_links": "روابط",
    "footer_features": "المميزات",
    "footer_newsletter": "النشرة الإخبارية",
    "footer_newsletter_text": "اشترك للحصول على آخر الأخبار والتحديثات.",
    "footer_language": "اللغة:",
    "footer_rights": "جميع الحقوق محفوظة.",

    // Dashboard
    "dashboard_title": "لوحة التحكم الزراعية",
    "overview": "نظرة عامة",
    "pivots": "المحاور",
    "real_time_sensors": "أجهزة الاستشعار في الوقت الحقيقي",
    "soil_temp": "درجة حرارة التربة",
    "soil_humidity": "رطوبة التربة",
    "air_humidity": "رطوبة الهواء",
    "luminosity": "الإضاءة",
    "agricultural_info": "المعلومات الزراعية",
    "crops_info": "المحاصيل:",
    "irrigation_info": "الري:",
    "season_info": "الموسم:",
    "yield_info": "المحصول:",
    "weather_title": "الطقس - بشار",
    "wind": "الرياح:",
    "humidity": "الرطوبة:",
    "max_temp": "الحد الأقصى:",
    "min_temp": "الحد الأدنى:",
    "forecast_title": "توقعات 5 أيام",
    "agricultural_zones": "خريطة المناطق الزراعية",
    "cultivated_areas": "المناطق المزروعة",
    "water_consumption": "استهلاك المياه",
    "average_yield": "متوسط المحصول",
    "completed_tasks": "المهام المكتملة",

    // History page
    "history": "السجل",
    "task_history": "سجل المهام",
    "time_period": "الفترة الزمنية",
    "all_time": "جميع الفترات",
    "by_week": "بالأسبوع",
    "by_month": "بالشهر",
    "all_pivots": "جميع المحاور",
    "all_categories": "جميع الفئات",
    "no_completed_tasks": "لم يتم العثور على مهام مكتملة",
    "tasks_appear_here": "ستظهر المهام المكتملة هنا",
    "filters": "تصفية",
    "type": "النوع",
    "all": "الكل",
    "tasks": "المهام",
    "date_range": "نطاق التاريخ",
    "today": "اليوم",
    "this_week": "هذا الأسبوع",
    "this_month": "هذا الشهر",
    "irrigation_pivots": "محاور الري",
    "add_pivot": "إضافة",
    "delete_pivot": "حذف",
    "plan_pivot": "تخطيط",

    // Settings page
    "language_settings": "إعدادات اللغة",
    "language_description": "اختر اللغة التي تريد استخدامها للمنصة.",
    "weather_config": "إعدادات الطقس",
    "weather_provider": "مزود بيانات الطقس",
    "select_provider": "اختر مزودًا",
    "provider_help": "اختر خدمة الطقس التي تريد استخدامها للبيانات.",
    "api_key": "مفتاح API",
    "api_key_help": "يمكنك الحصول على مفتاح API عن طريق التسجيل في موقع المزود المحدد.",
    "about": "حول",
    "about_description": "تم تصميم منصة إدارة الزراعة هذه لمساعدة المزارعين في منطقة بشار على تحسين أنشطتهم من خلال بيانات الطقس الدقيقة وأدوات التخطيط.",
    "version": "الإصدار:",
    "last_update": "آخر تحديث:",
    "weather_settings_saved": "تم حفظ إعدادات الطقس بنجاح!",
    "language_settings_saved": "تم تغيير اللغة بنجاح!",

    // User management
    "user_management": "إدارة المستخدمين",
    "user_list": "قائمة المستخدمين",
    "add_user": "إضافة مستخدم",
    "edit_user": "تعديل المستخدم",
    "delete_user": "حذف المستخدم",
    "user_id": "المعرف",
    "user_status": "الحالة",
    "user_name": "الاسم",
    "user_email": "البريد الإلكتروني",
    "user_role": "الدور",
    "user_actions": "الإجراءات",
    "online": "متصل",
    "offline": "غير متصل",
    "admin": "مدير",
    "user": "مستخدم",
    "full_name": "الاسم الكامل",
    "password": "كلمة المرور",
    "confirm_password": "تأكيد كلمة المرور",
    "search_users": "البحث بالاسم أو البريد الإلكتروني...",
    "all_roles": "جميع الأدوار",
    "all_statuses": "جميع الحالات",
    "no_users_found": "لم يتم العثور على مستخدمين",
    "delete_confirmation": "هل أنت متأكد؟",
    "delete_warning": "لا يمكن التراجع عن هذا الإجراء!",
    "confirm_delete": "نعم، احذف!",
    "user_added": "تمت إضافة المستخدم بنجاح!",
    "user_updated": "تم تحديث المستخدم بنجاح!",
    "user_deleted": "تم حذف المستخدم بنجاح!",

    // Profile page
    "personal_info": "المعلومات الشخصية",
    "phone": "الهاتف",
    "location": "الموقع",
    "bio": "السيرة الذاتية",
    "security": "الأمان",
    "current_password": "كلمة المرور الحالية",
    "new_password": "كلمة المرور الجديدة",
    "update": "تحديث",
    "profile_updated": "تم تحديث الملف الشخصي بنجاح!",
    "password_updated": "تم تحديث كلمة المرور بنجاح!",
    "recent_activity": "النشاط الأخير",
    "no_activity": "لا يوجد نشاط حديث",

    // Planning page
    "activity_calendar": "تقويم الأنشطة",
    "pivot_planning": "تخطيط المحاور",
    "stats": "الإحصائيات",
    "tasks_month": "مهام هذا الشهر",
    "date": "التاريخ",
    "pivot": "المحور",
    "time": "الوقت",
    "activity": "النشاط",
    "category": "الفئة",
    "actions": "الإجراءات",
    "new_planning": "تخطيط جديد",
    "irrigation": "الري",
    "fertilization": "التسميد",
    "harvest": "الحصاد",
    "treatment": "المعالجة",
    "other": "أخرى",
    "select_pivots": "اختر المحاور",
    "fill_all_fields": "يرجى ملء جميع الحقول المطلوبة واختيار محور واحد على الأقل",
    "time_error": "يجب أن يكون وقت الانتهاء بعد وقت البدء",
    "planning_saved": "تم حفظ التخطيط لـ {count} محور!",
    "delete_planning_confirm": "هل تريد حقًا حذف هذا التخطيط؟",
    "yes_delete": "نعم، احذفه!",
    "planning_deleted": "تم حذف التخطيط بنجاح!",
    "delete_all_confirm": "هل أنت متأكد من حذف جميع التخطيطات؟",
    "irreversible_action": "هذا الإجراء لا رجعة فيه!",
    "yes_delete_all": "نعم، احذف الكل!",
    "all_plannings_deleted": "تم حذف جميع التخطيطات!"
  }
};

// Langue actuelle
let currentLanguage = localStorage.getItem('language') || 'fr';

// Fonction pour charger la langue
function loadLanguage() {
  // Récupérer la langue depuis localStorage
  const savedLanguage = localStorage.getItem('language') || 'fr';
  currentLanguage = savedLanguage;

  // Appliquer les traductions
  applyTranslations();

  // Mettre à jour l'attribut lang de la balise html
  document.documentElement.lang = currentLanguage;

  // Si la langue est l'arabe, ajouter une classe pour le style RTL
  if (currentLanguage === 'ar') {
    document.body.classList.add('rtl');
  } else {
    document.body.classList.remove('rtl');
  }
}

// Fonction pour changer la langue
function changeLanguage(language) {
  // Sauvegarder la langue dans localStorage
  localStorage.setItem('language', language);
  currentLanguage = language;

  // Appliquer les traductions
  applyTranslations();

  // Mettre à jour l'attribut lang de la balise html
  document.documentElement.lang = language;

  // Gérer le style RTL pour l'arabe
  if (language === 'ar') {
    document.body.classList.add('rtl');
  } else {
    document.body.classList.remove('rtl');
  }

  // Mettre à jour la date si nécessaire
  if (typeof updateLastUpdateDate === 'function') {
    updateLastUpdateDate();
  }

  // Mettre en évidence le bouton de la langue actuelle si on est sur la page des paramètres
  if (typeof highlightCurrentLanguage === 'function') {
    highlightCurrentLanguage();
  }

  // Afficher un message de confirmation si la fonction existe
  if (typeof showAlert === 'function') {
    const message = translations[language].language_settings_saved;
    showAlert('success', message);
  }
}

// Fonction pour appliquer les traductions
function applyTranslations() {
  // Appliquer les traductions à tous les éléments avec la classe lang-text
  document.querySelectorAll('.lang-text').forEach(element => {
    const key = element.getAttribute('data-lang-key');
    if (key && translations[currentLanguage][key]) {
      element.textContent = translations[currentLanguage][key];
    }
  });

  // Mettre à jour les placeholders
  document.querySelectorAll('[data-lang-placeholder]').forEach(element => {
    const key = element.getAttribute('data-lang-placeholder');
    if (key && translations[currentLanguage][key]) {
      element.placeholder = translations[currentLanguage][key];
    }
  });
}

// Initialiser la langue au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
  loadLanguage();
});
