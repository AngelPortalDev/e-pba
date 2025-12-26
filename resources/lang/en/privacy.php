<?php

return [
    'title' => 'Privacy Policy',
    'effective_date' => 'Effective from August 2024',
    'intro' => 'Welcome to E-PBA! At E-PBA <a href=":webiste_url">(www.e-pba.fr)</a>, we set a high priority on safeguarding your privacy. This Privacy Policy outlines how we manage your personal data, ensuring compliance with regulations such as the General Data Protection Regulation (GDPR) and The Data Protection Act (DPA) of Malta. We aim to enhance trust and transparency with our users regarding handling their data on E-PBA and its website <a href=":webiste_url">(www.e-pba.fr)</a>',

    'breadcrumb_title' => 'Privacy Policy',

    'sections' => [
        'who_we_are' => [
            'title' => ' Who are we; What do we do?',
            'content' => 'This policy has been prepared by Paris Business Academy, a company incorporated under the laws of Malta and bearing company registration number C 94041 (hereinafter "Ascencia", "we", "us" or "our").',
            'portal_description' => 'We manage and operate a portal, currently accessible online at https://www.e-pba.fr("the Portal"), through which we make available a number of online courses (each "Online Course" and collectively "Online Courses").',
            'tou_reference' => 'For further information about the Portal, the Online Courses and our respective rights and obligations, please refer to our Terms of Use ("TOU").'
        ],

        'policy_coverage' => [
            'title' => ' What does this policy cover?',
            'content' => 'This policy provides an overview of the personal data we process when we act as data controllers in connection with your use of our Portal, your participation in, and our delivery of, Online Courses. This policy also outlines how we collect or otherwise procure this personal data, what we do with such personal data and generally how we comply with the provisions of laws relating to the protection of personal data as applicable to us, in particular Regulation (EU) 2016/679 ("GDPR").',
            'clarification' => 'Throughout this document, we will be using certain specific terms. Since our intention is that this document is easily understood, we would like to clarify what these terms are intended to refer to. Naturally, if anything is unclear, please do not hesitate to get in touch with us.',
            'definitions' =>  'In terms of the provisions of the GDPR, the term "personal data" is defined as "any information relating to an identified or identifiable natural person ("data subject")". Furthermore, the term "processing" is also given a wide meaning and is defined as "any operation or set of operations which is performed on personal data or on sets of personal data, whether or not by automated means." This includes collection, recording, storage, adaptation, and use of personal data.',

        ],

        'data_types' => [
            'title' => 'What types of personal data will we be processing as data controller? How do we get such personal data?',
            'intro' => 'We have grouped the personal data that we receive, use or otherwise process in the following categories:',
            'categories' => [
                'heading'=>[
                    'title'=>'Title',
                    'description'=>'Description',
                    "source"=>"Source"
                ],
                'profile' => [
                    'title' => 'Profile Information',
                    'description' => 'This relates to (a) the details that you provide to us when opening an account, including your name, surname, and email address, (b) the details that you provide us when you apply to enrol in one of our Courses, comprising of your ID card and/or passport and/or driving license, relevant academic certificates and proof of English, (c) your profile photo, should you choose to upload one, and (d) generally all other information that we require to manage and operate your account.',
                    'source' => 'Directly from you when you create and manage your account.'
                ],
                'interaction' => [
                    'title' => 'Interaction Information',
                    'description' => 'This comprises any information, data or material that is exchanged with us and is not covered in any of the other categories set out in this table.',
                    'source' => 'Directly from your interactions and communications with us.'
                ],
                'learning_information' => [
                    'title' => 'Learning Information',
                    'description' => 'When you enrol in our Courses, access the content that we upload and engage in our learning activities, we gather information about your participation in Courses, assignments, quizzes and exams. We also collect data on your interactions with instructors, the exchanges with the e-mentors and any other submissions you make to fulfil the requirements of the Course and related content.',
                    'source' => 'Directly from your activities and submissions in Courses, supplemented by your interactions with instructors and e-mentors.'
                ],
                'shared_content' => [
                    'title' => 'Shared Content',
                    'description' => 'Certain features of our offerings and our Portal allow you to interact with other users and/or share content. This includes uploading comments on our discussion boards, posting reviews, asking or answering questions, sending messages to other students, and uploading photos or other works. Certain types of Shared Content, such as your contributions on our discussion boards, may be visible to other users.	',
                    'source' => 'Directly from your activities on our platforms where you interact with others and share content.'
                ],
                'financial_information' => [
                    'title' => 'Financial Information',
                    'description' => 'This includes details concerning the necessary details to process your purchases, the fees owed to us, banking details and other data relative to the aforementioned.	',
                    'source' => 'Directly from your financial transactions with us, including payment details and banking information.'
                ],
                'usage_information' => [
                    'title' => 'Usage Information',
                    'description' => 'When you access our Portal, enrol in the Courses or participate in our offerings, we also receive certain types of personal data automatically, such as the sections you have visited, the content you have accessed and the frequency and duration of your visits. In addition to the above, please note that we will also collect certain data about your device or browser automatically via log files, such as your Media Access Control (MAC) address, device ID, operating system name and version, browser type, and device manufacturer and model. We may also collect your IP address. We use data about your device to ensure our solutions function properly, diagnose server problems, and administer our software solutions and the services we provide.	',
                    'source' => 'Automatically as described in the second column.'
                ],

            ]
        ],

        'data_usage' => [
            'title' => ' How do we use personal data? What is the legal basis for processing personal data?',
            'primary_objective' => 'Our primary objectives in processing personal data is to deliver our Courses and to ensure compliance with our duties and obligations, whether legal or contractual (including discharging our obligations pursuant to the TOU).',
            'legal_basis' => 'We will process personal data when we have a proper reason for doing so. In particular, the legal basis we rely upon to process personal data is further set out in the table hereunder:',
            'purposes' => [
                 'heading'=>[
                    'title'=>'Purpose',
                    'type'=>'Type',
                    "basis"=>"Lawful basis"
                ],
                'onboarding' => [
                    'title' => 'To complete our onboarding process',
                    'description' => 'This includes setting up your account, verifying your identity, setting up your student profile, and ensuring that you have access to the courses you enrol in.',
                    'data_types' => 'Profile Information; Interaction Information',
                    'basis' => 'Legal obligation (GDPR Article 6(1)(c));'
                ],
                'deliver_courses' => [
                    'title' => 'To deliver our Courses',
                    'description' => 'We aim to deliver high-quality educational content and interactive learning experiences, tailored to meet your learning needs and objectives.',
                    'data_types' => 'Profile Information; Learning Information; Interaction Information;',
                    'basis' => 'Contractual necessity (GDPR Article 6(1)(b)).'
                ],
                'assessments' => [
                    'title' => 'To make our reviews, evaluations and assessments.',
                    'description' => 'To successfully achieve a passing grade, you are required to undertake various assessments, such as quizzes, assignments, and exams. These are designed to evaluate your understanding of the course material and demonstrate your proficiency in the subject matter.',
                    'data_types' => 'Profile Information; Learning Information',
                    'basis' => 'Contractual necessity (GDPR Article 6(1)(b)).<br> <p>Necessary for our legitimate interests and that of our students (GDPR, Article 6(1)(f)) – for educational quality and integrity.</p>'
                ],
                'our_portal' => [
                    'title' => 'To provide interactive features within our Portal',
                    'description' => 'We allow you to communicate with our lecturers and e-mentors. Furthermore, we also provide certain features which allow you to share content with other students and participants.',
                    'data_types' => 'Profile Information; Shared Content; Usage Information;',
                    'basis' => 'Contractual necessity (GDPR Article 6(1)(b)).<br><br>
                                Consent (GDPR, Article 6(1)(a)), in relation to the Shared Content published or uploaded to the message boards.<br><br>
                                Necessary for our legitimate interests and that of our students (GDPR, Article 6(1)(f)) - to enhance active participation from our students.'
                ],
                'applicable_laws' => [
                    'title' => 'To ensure that our Courses, offerings and any of our engagement complies fully with all applicable laws.',
                    'description' => 'We manage our educational services to fully comply with educational standards, accreditation requirements, and all other relevant legislation.',
                    'data_types' => 'Profile Information; Service Information; Financial Information; Interaction Information; Usage Information;',
                    'basis' => 'Legal obligation (GDPR Article 6(1)(c))<br><br>
                                Necessary for our legitimate interests (GDPR, Article 6(1)(f)) - to safeguard our reputation'
                ],
                'customer_service' => [
                    'title' => 'To manage our relationship with you, including the provision of customer service',
                    'description' => 'This encompasses ongoing customer support, handling inquiries, and ensuring satisfactory communication throughout your learning journey.',
                    'data_types' => 'Profile Information; Interaction Information; Shared Content	',
                    'basis' => 'Legal obligation (GDPR, Article 6(1)(c))
                                Contractual necessity (GDPR, Article 6(1)(b))
                                Necessary for our legitimate interests (GDPR, Article 6(1)(f)) - to keep our records updated.
                                Consent (GDPR, Article 6(1)(a)).'
                ],
                'payments_fees' => [
                    'title' => 'To manage payments and fees',
                    'description' => 'We process tuition and other fees associated with our courses, which may include financial aid management and processing refunds where applicable.',
                    'data_types' => 'Profile Information; Financial Information; Interaction Information',
                    'basis' => 'Contractual necessity (GDPR, Article 6(1)(b))
                                Necessary for our legitimate interests (GDPR, Article 6(1)(f)) – to collect the payment due to us.'
                ],
                'database_marketing' => [
                    'title' => 'To maintain our contact database for marketing',
                    'description' => 'We manage and update our list of contacts to send you information about new courses, special offers, and upcoming events through various communication channels.',
                    'data_types' => 'Profile Information; Service Information; Interaction Information',
                    'basis' => 'Consent (GDPR, Article 6(1)(a)).
                                Necessary for our legitimate interests (GDPR, Article 6(1)(f)) – to keep our records updated; to enhance our business and client base.'
                ],

                'analytics' => [
                    'title' => 'Business Intelligence & Analytics',
                    'description' => 'To collect and anonymize data for statistical and benchmarking purposes.',
                    'data_types' => 'Profile Information; Service Information; Interaction Information	',
                    'basis' => 'Necessary for our legitimate interests (GDPR, Article 6(1)(f)) – to improve user experience, our Courses and offerings.'
                ],
                'our_interests' => [
                    'title' => 'To safeguard our interests',
                    'description' => 'This includes keeping our infrastructure secure, through security monitoring to detect, prevent and respond to suspicious activity, fraud, intellectual property infringement, violations of our terms or law and for other similar purposes; to establish, exercise or defend legal claims',
                    'data_types' => 'Profile Information; Interaction Information; Service Information; Financial Information; Usage Information.',
                    'basis' => 'Necessary for our legitimate interests (GDPR, Article 6(1)(f)) – to safeguard our interests and infrastructure).
                                Legal obligation (GDPR Article 6(1)(c))'
                ],
                'business_transactions' => [
                    'title' => 'To facilitate business transactions',
                    'description' => 'To make certain information available to third parties that may be interested in acquiring our business (either prior to or as part of the transaction). This includes, amongst others, any merger, sale, restructure, acquisition, joint venture, assignment, transfer, or other disposition of all or any portion of our business, assets, or stock.',
                    'data_types' => 'To make certain information available to third parties that may be interested in acquiring our business (either prior to or as part of the transaction). This includes, amongst others, any merger, sale, restructure, acquisition, joint venture, assignment, transfer, or other disposition of all or any portion of our business, assets, or stock.',
                    'basis' => 'Necessary for our legitimate interests (GDPR, Article 6(1)(f)) – to ensure that we are able to sell our business, should we decide to do so).'
                ],


            ],
            'title_purpose' => 'Change of purpose',
            'change_of_purpose' => 'We will use and process personal information solely for the purposes for which it was initially collected, unless we reasonably believe there is a need to use it for a different yet compatible reason. In the event we intend to use personal information for an unrelated purpose, we will inform the relevant data subjects and provide an explanation of the legal basis that permits us to do so.'
        ],



        'data_sharing' => [
            'title' => 'Do we share or make personal data available with third parties?',
            'content' => 'We will share personal data with third parties where required by law, where it is necessary to administer the relationship with our clients, and as otherwise provided hereunder.',
            'conetnt1'=>'Furthermore, we will also share your personal data as follows:',
            'parties' => [
                'lecturers' => 'Lecturers and e-mentors - Your personal data may be shared with lecturers and e-mentors, including those who are not our employees but third-party contractors, who are involved in delivering the Courses you enrol in and administering part of your experience with us. This sharing facilitates personalised guidance, monitors your progress, and enables effective communication.',
                'anti_cheating' => 'Anti-cheating tools and procedures – We share relevant personal data with third-party service providers that operate anti-cheating tools for various assessments, including live exams. For live exams, data such as registration details, activity logs, and random screenshots taken from the participant’s webcam are shared in real-time with proctoring services to ensure the integrity and security of the examinations. This live data sharing is critical for immediate detection and response to any potential academic dishonesty. Additionally, for other types of assessments, we may share similar data to facilitate ongoing monitoring and uphold our academic honesty policies. This comprehensive approach helps maintain the integrity of all assessments conducted within our educational framework.',
                'thirdparty'=>'Third-party service providers – from time to time, and always subject to us complying in full with Article 28 GDPR, we engage a number of third parties to provide us with certain services. In doing so, certain types of personal data may be required to be provided to such third-party service providers. These include third parties providing legal advice, audit, payment services and gateways, banking services, sales and marketing, customer support, AML and sanction screening & IT services;',
                'd'=>'Our insurers and insurance brokers;',
                'e'=>'Regulatory authorities, departments or law enforcement agencies, when we are required, or permitted to do so by law;',
                'f'=>'Any other person or entity but solely when we are expressly authorised to do so, such as when you provide us with your consent;',
                'g'=>'A prospective buyer or any of its advisors, where relevant, in the course of a due diligence exercise or as part of a corporate transaction. In this situation we will, so far as possible, share anonymised data with the other parties before the transaction completes.',
            ],
            'wemay'=>'We may also process your personal data to comply with our regulatory requirements or in the course of dialogue with our regulators as applicable, which may include disclosing your personal data to government, regulatory or law enforcement agencies in connection with enquiries, proceedings or investigations by such parties anywhere in the world or where compelled to do so. Where permitted, or unless to do so would prejudice the prevention or detection of a crime, we will direct any such request to you or notify you before responding.',
            'safeguards' => 'Prior to sharing data with a third-party service provider, we require them to commit in implementing appropriate security measures to protect your personal information in line with our policies. We do not allow our third-party service providers to use your personal data for their own purposes. We only permit them to process your personal data for specified purposes and in accordance with our instructions.'
        ],

        'data_transfers' => [
            'title' => ' Is the information transferred outside of the EEA?',
            'content' => 'Currently, the majority of the personal data we use and process is hosted in Malta and the European Economic Area (EEA). It is however possible that personal data will be made available or otherwise processed outside of the EUEEA, namely when we engage third-party contractors.',
            'india_example' => 'In fact, currently certain technical support services are provided by a trusted third-party vendor based in India. In the provision of such technical support services, this trusted third-party vendor may be required to access or otherwise process personal data.',
            'safeguards' => 'When we engage third-party vendors based outside the EEA, we will take adequate measures to ensure that personal data is safeguarded to the same standards as it would have been if processed in the EU, by relying on one of the following:',
            'safeguard_methods1'=>'When we engage third-party vendors based outside the EEA, we will take adequate measures to ensure that personal data is safeguarded to the same standards as it would have been if processed in the EU, by relying on one of the following:',
            'safeguard_methods2' => 'We will enter into agreements that impose a legal obligation on the recipient to protect personal data in accordance with the provisions of the GDPR.',

        ],

        'rights' => [
            'title' => ' Data Subject Rights',
            'intro' => 'The GDPR grants data subjects a number of rights that can be exercised in certain circumstances, including:',
            'rights_list' => [
                'access' => 'Right of access (subject access request) - This right allows data subjects to request and obtain confirmation on whether we are processing their personal data.',
                'rectification' => 'Right of rectification – data subjects have the right to request that we correct any inaccuracies or incomplete personal data held about them.',
                'c'=>'Right of erasure – In terms of this right, commonly known as the "Right to be Forgotten," data subjects can request the deletion of their personal data under certain circumstances, particularly when the data is no longer necessary for the purpose for which it was collected.',
                'd'=>'Right of restriction – data subjects can request the limitation of the processing of their personal data in specific situations. This right is relevant, for instance, when the data subject is contesting the accuracy of the data, or the processing is deemed unlawful.',
                'e'=>'Right to object - This right enables the data subjects to object to the processing of their personal data, including profiling, for reasons related to their particular situation',
                'f'=>'Right of data portability – data subjects have the right to receive their personal data in a structured, commonly used, and machine-readable format.',
            ],
            'list1'=>'In those occasions where we have indicated that we are basing our processing on our legitimate interest, please note that in terms of Article 21 GDPR, data subjects have the right to object to that processing.',
            'withdrawal' => 'Where the legal basis of processing is based solely on the data subject\'s consent, the data subjects may withdraw such consent at any time by notifying us accordingly. This shall be without prejudice to the lawfulness of processing based on consent before such withdrawal.',
            'contact' => 'For more information about these rights and how to exercise them (when we are acting in our capacity as data controllers), kindly contact us on the contact details set out hereunder.'
        ],

        // Continue with remaining sections

        'changes' => [
            'title' => 'Changes to the Privacy Policy',
            'content' => 'We may alter these terms at any time, but in any case we will inform you accordingly, by means we deem reasonable in the circumstances. In the event of any conflict between the current version of these terms and any previous version(s), the provisions current and in effect shall prevail unless it is expressly stated otherwise.'
        ]
    ],
    'section.5'=>'Is the provision of personal data mandatory?',
    '5.content'=> 'While we respect decisions by data subjects not to share personal data, please be aware that there may be limitations in our ability to accommodate such choices. In particular, we are unable to enrol you in any of our Courses unless you open account and thus provide us with Profile Information. Furthermore, it would be impossible for us to deliver our Courses and discharge our obligations pursuant to the TOU if you do not upload your assignments or participate in the exams or the assessment associated with that particular course.',

     '6.title'=>'What about data concerning third parties? Are there any additional obligations or duties?',
     '6.content'=>'To safeguard privacy and ensure that we comply with our legal obligatiosn, we require that you only provide personal data that pertains directly to yourself.',

     '7.title'=>'Do we collect special categories of data?',
     '7.content'=>'Under the GDPR, personal data revealing racial or ethnic origin, political opinions, religious or philosophical beliefs, or trade union membership, and the processing of genetic data, biometric data for the purpose of uniquely identifying a natural person, data concerning health or data concerning a natural person’s sex life or sexual orientation is deemed to be “special categories of personal data” and require a higher level of protection. We need to have further justification for collecting, storing and using this type of personal information. We have in place appropriate safeguards which we are required by law to maintain when processing such data.
                     <br>We do not collect any such special category of personal data.',

     '8.title'=>'Do we collect data related to criminal convictions and offences?',
     '8.content'=>'No',


     '12.title'=>'Will there be any fully automated decision as per Article 22 GDPR?',
        '12.1'=>'We utilize technology, including AI and machine learning, to enhance the efficiency and effectiveness of our services, while ensuring that your data protection and fundamental rights are safeguarded.',
        '12.2'=>'Automated Multiple-Choice Exam Marking: Certain exams consist of multiple-choice questions, where answers are evaluated solely through automated means without human intervention. This process is necessary for the performance of our contract with you. By agreeing to the terms, you also provide your explicit consent for this automated evaluation. If you believe there has been an error in your marking, please contact us, and we will arrange for a human reassessment of your results.',
        '12.3'=>'Turnitin for Plagiarism Detection: We use Turnitin, a tool powered by AI, to check for plagiarism. However, if your work exceeds the plagiarism threshold, it will be reviewed by a human. As this process includes human oversight, it is not considered fully automated under Article 22 GDPR, and the provisions of this article do not apply.',
        '12.4'=>'We are committed to deploying technology responsibly and ensuring your rights are respected in all automated or partially automated processes. If you have any questions or concerns, please feel free to reach out to us.',

        '13.title'=>'13. For how long do we retain personal data?',
        '13.1'=>'The length of time for which we hold personal data depends on a number of factors, such as regulatory rules and any legal requirements. We also consider the amount, nature, and sensitivity of the personal data, the potential risk of harm from unauthorised use or disclosure of personal data, the purposes for which we process personal data and whether we can achieve those purposes through other means.',
        '13.2'=>'For further information about our data retention policies, please get in touch with our data privacy manager on the contact details set out hereunder.',

        '14.title'=>'14. Do you need more information about our data handling policies?',
        '14.1'=>'If you need more information about this this privacy notice or how we handle personal information, please contact our data privacy manager, on info@eascencia.mt or through our chat function on our website',
        '14.1.1'=>'Our registered address is situated at: 23, Vincenzo Dimech Street, Floriana FRN 1502 Malta.',

       '15.title'=>'15. What responsibilities do clients and data subjects have regarding the processing of personal data?',
       '15.1'=>'Privacy and data protection is a two-way street, and while we strive to uphold it diligently, the active participation of everyone is crucial. This means that along with enjoying privacy rights, data subjects also have certain responsibilities. As part of these obligations, we anticipate that data subjects take reasonable measures to assist us in effectively safeguarding and managing your privacy.',
       '15.2'=>'For instance, to ensure that we maintain accurate, complete, and up-to-date personal information, we kindly you to promptly notify us if personal details previously submitted to us become inaccurate, incomplete, or outdated.',

       '16.title' => '16. Is it possible to file a complaint?',
       '16.1'=>'We go to great lengths to ensure that we handle personal data responsibly. If there are any concerns or issues with anything related to these matters, please do not hesitate to get in touch with us and we assure you that we will do our utmost to address your concerns.',
        '16.2'=>'In any case, if you are not satisfied with the way we manage personal data, you have the right to file a complaint with any relevant data protection authority (particularly the one situated where you habitually reside). Contact details of the competent authority in Malta are as follows:',


    'version' => 'Version 1',
    'date' => 'Date: August 2024',

    'contact_email' => 'info@paris-business-academy.com',
    'website_url' => 'https://www.e-pba.fr',
    'idpc' => [
        'address' => 'Information and Data Protection Commissioner, Floor 2, Airways House, High Street, Sliema, SLM 1549, Malta',
        'phone' => '(+356) 2328 7100',
        'email' => 'idpc.info@idpc.org.mt'
    ]
];
