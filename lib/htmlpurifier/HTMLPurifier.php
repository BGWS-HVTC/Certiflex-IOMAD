<?php

/*! @mainpage
 *
 * HTML Purifier is an HTML filter that will take an arbitrary snippet of
 * HTML and rigorously test, validate and filter it into a version that
 * is safe for output onto webpages. It achieves this by:
 *
 *  -# Lexing (parsing into tokens) the document,
 *  -# Executing various strategies on the tokens:
 *      -# Removing all elements not in the whitelist,
 *      -# Making the tokens well-formed,
 *      -# Fixing the nesting of the nodes, and
 *      -# Validating attributes of the nodes; and
 *  -# Generating HTML from the purified tokens.
 *
 * However, most users will only need to interface with the HTMLPurifier
 * and HTMLPurifier_Config.
 */

/*
    HTML Purifier 4.17.0 - Standards Compliant HTML Filtering
    Copyright (C) 2006-2008 Edward Z. Yang

    This library is free software; you can redistribute it and/or
    modify it under the terms of the GNU Lesser General Public
    License as published by the Free Software Foundation; either
    version 2.1 of the License, or (at your option) any later version.

    This library is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
    Lesser General Public License for more details.

    You should have received a copy of the GNU Lesser General Public
    License along with this library; if not, write to the Free Software
    Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 */

/**
 * Facade that coordinates HTML Purifier's subsystems in order to purify HTML.
 *
 * @note There are several points in which configuration can be specified
 *       for HTML Purifier.  The precedence of these (from lowest to
 *       highest) is as follows:
 *          -# Instance: new HTMLPurifier($config)
 *          -# Invocation: purify($html, $config)
 *       These configurations are entirely independent of each other and
 *       are *not* merged (this behavior may change in the future).
 *
 * @todo We need an easier way to inject strategies using the configuration
 *       object.
 */
class HTMLPurifier
{

    /**
     * Version of HTML Purifier.
     * @type string
     */
    public $version = '4.17.0';

    /**
     * Constant with version of HTML Purifier.
     */
    const VERSION = '4.17.0';

    // BGWS Modification START
    // Author - Mike Robb
    // Jira ticket - CER-56
    // Defining all classes in the HTMLPurifier to allow for unserialize to be used safely
    public static $unserialize_safe_classes = [
        // AttrDef
        HTMLPurifier_AttrDef::class,
        // AttrDef > CSS
        HTMLPurifier_AttrDef_CSS_AlphaValue::class,
        HTMLPurifier_AttrDef_CSS_Background::class,
        HTMLPurifier_AttrDef_CSS_BackgroundPosition::class,
        HTMLPurifier_AttrDef_CSS_Border::class,
        HTMLPurifier_AttrDef_CSS_Color::class,
        HTMLPurifier_AttrDef_CSS_Composite::class,
        HTMLPurifier_AttrDef_CSS_DenyElementDecorator::class,
        HTMLPurifier_AttrDef_CSS_Filter::class,
        HTMLPurifier_AttrDef_CSS_Font::class,
        HTMLPurifier_AttrDef_CSS_Ident::class,
        HTMLPurifier_AttrDef_CSS_ImportantDecorator::class,
        HTMLPurifier_AttrDef_CSS_Length::class,
        HTMLPurifier_AttrDef_CSS_ListStyle::class,
        HTMLPurifier_AttrDef_CSS_Multiple::class,
        HTMLPurifier_AttrDef_CSS_Number::class,
        HTMLPurifier_AttrDef_CSS_Percentage::class,
        HTMLPurifier_AttrDef_CSS_TextDecoration::class,
        HTMLPurifier_AttrDef_CSS_URI::class,
        // AttrDef > HTML
        HTMLPurifier_AttrDef_HTML_Bool::class,
        HTMLPurifier_AttrDef_HTML_Class::class,
        HTMLPurifier_AttrDef_HTML_Color::class,
        HTMLPurifier_AttrDef_HTML_ContentEditable::class,
        HTMLPurifier_AttrDef_HTML_FrameTarget::class,
        HTMLPurifier_AttrDef_HTML_ID::class,
        HTMLPurifier_AttrDef_HTML_Length::class,
        HTMLPurifier_AttrDef_HTML_LinkTypes::class,
        HTMLPurifier_AttrDef_HTML_MultiLength::class,
        HTMLPurifier_AttrDef_HTML_Nmtokens::class,
        HTMLPurifier_AttrDef_HTML_Pixels::class,
        // AttrDef > URI
        HTMLPurifier_AttrDef_URI_Email::class,
        HTMLPurifier_AttrDef_URI_Host::class,
        HTMLPurifier_AttrDef_URI_IPv4::class,
        HTMLPurifier_AttrDef_URI_IPv6::class,
        // AttrDef > URI > Email
        HTMLPurifier_AttrDef_URI_Email_SimpleCheck::class,
        // AttrDef
        HTMLPurifier_AttrDef_Clone::class,
        HTMLPurifier_AttrDef_CSS::class,
        HTMLPurifier_AttrDef_Enum::class,
        HTMLPurifier_AttrDef_Integer::class,
        HTMLPurifier_AttrDef_Lang::class,
        HTMLPurifier_AttrDef_Switch::class,
        HTMLPurifier_AttrDef_Text::class,
        HTMLPurifier_AttrDef_Uri::class,

        // AttrTransform
        HTMLPurifier_AttrTransform_Background::class,
        HTMLPurifier_AttrTransform_BdoDir::class,
        HTMLPurifier_AttrTransform_BgColor::class,
        HTMLPurifier_AttrTransform_BoolToCSS::class,
        HTMLPurifier_AttrTransform_Border::class,
        HTMLPurifier_AttrTransform_EnumToCSS::class,
        HTMLPurifier_AttrTransform_ImgRequired::class,
        HTMLPurifier_AttrTransform_ImgSpace::class,
        HTMLPurifier_AttrTransform_Input::class,
        HTMLPurifier_AttrTransform_Lang::class,
        HTMLPurifier_AttrTransform_Length::class,
        HTMLPurifier_AttrTransform_Name::class,
        HTMLPurifier_AttrTransform_NameSync::class,
        HTMLPurifier_AttrTransform_Nofollow::class,
        HTMLPurifier_AttrTransform_SafeEmbed::class,
        HTMLPurifier_AttrTransform_SafeObject::class,
        HTMLPurifier_AttrTransform_SafeParam::class,
        HTMLPurifier_AttrTransform_ScriptRequired::class,
        HTMLPurifier_AttrTransform_TargetBlank::class,
        HTMLPurifier_AttrTransform_TargetNoopener::class,
        HTMLPurifier_AttrTransform_TargetNoreferrer::class,
        HTMLPurifier_AttrTransform_Textarea::class,
        
        // ChildDef
        HTMLPurifier_ChildDef_Chameleon::class,
        HTMLPurifier_ChildDef_Custom::class,
        HTMLPurifier_ChildDef_Empty::class,
        HTMLPurifier_ChildDef_List::class,
        HTMLPurifier_ChildDef_Optional::class,
        HTMLPurifier_ChildDef_Required::class,
        HTMLPurifier_ChildDef_StrictBlockquote::class,
        HTMLPurifier_ChildDef_Table::class,

        // ConfigSchema > Builder
        HTMLPurifier_ConfigSchema_Builder_ConfigSchema::class,
        HTMLPurifier_ConfigSchema_Builder_Xml::class,

        // ConfigSchema > Interchange
        HTMLPurifier_ConfigSchema_Interchange_Directive::class,
        HTMLPurifier_ConfigSchema_Interchange_Id::class,
        
        // ConfigSchema
        HTMLPurifier_ConfigSchema::class,
        HTMLPurifier_ConfigSchema_Interchange::class,
        HTMLPurifier_ConfigSchema_Exception::class,
        HTMLPurifier_ConfigSchema_Interchange::class,
        HTMLPurifier_ConfigSchema_InterchangeBuilder::class,
        HTMLPurifier_ConfigSchema_Validator::class,
        HTMLPurifier_ConfigSchema_ValidatorAtom::class,

        // DefinitionCache > Decorator
        HTMLPurifier_DefinitionCache_Decorator_Cleanup::class,
        HTMLPurifier_DefinitionCache_Decorator_Memory::class,
        // DefinitionCache > Serialize

        // DefinitionCache
        HTMLPurifier_DefinitionCache_Decorator::class,
        HTMLPurifier_DefinitionCache_Serializer::class,
        HTMLPurifier_DefinitionCache_Null::class,

        // EntityLookup
        HTMLPurifier_EntityLookup::class,

        // Filter
        HTMLPurifier_Filter::class,
        HTMLPurifier_Filter_ExtractStyleBlocks::class,
        HTMLPurifier_Filter_YouTube::class,

        // HTMLModule > Tidy
        HTMLPurifier_HTMLModule_Tidy_Name::class,
        HTMLPurifier_HTMLModule_Tidy_Proprietary::class,
        HTMLPurifier_HTMLModule_Tidy_Strict::class,
        HTMLPurifier_HTMLModule_Tidy_Transitional::class,
        HTMLPurifier_HTMLModule_Tidy_XHTML::class,
        HTMLPurifier_HTMLModule_Tidy_XHTMLAndHTML4::class,

        // HTMLModule
        HTMLPurifier_HTMLModule_Bdo::class,
        HTMLPurifier_HTMLModule_CommonAttributes::class,
        HTMLPurifier_HTMLModule_Edit::class,
        HTMLPurifier_HTMLModule_Forms::class,
        HTMLPurifier_HTMLModule_Hypertext::class,
        HTMLPurifier_HTMLModule_Iframe::class,
        HTMLPurifier_HTMLModule_Image::class,
        HTMLPurifier_HTMLModule_Legacy::class,
        HTMLPurifier_HTMLModule_List::class,
        HTMLPurifier_HTMLModule_Name::class,
        HTMLPurifier_HTMLModule_Nofollow::class,
        HTMLPurifier_HTMLModule_NonXMLCommonAttributes::class,
        HTMLPurifier_HTMLModule_Object::class,
        HTMLPurifier_HTMLModule_Presentation::class,
        HTMLPurifier_HTMLModule_Proprietary::class,
        HTMLPurifier_HTMLModule_Ruby::class,
        HTMLPurifier_HTMLModule_SafeEmbed::class,
        HTMLPurifier_HTMLModule_SafeObject::class,
        HTMLPurifier_HTMLModule_SafeScripting::class,
        HTMLPurifier_HTMLModule_StyleAttribute::class,
        HTMLPurifier_HTMLModule_Tables::class,
        HTMLPurifier_HTMLModule_Target::class,
        HTMLPurifier_HTMLModule_TargetBlank::class,
        HTMLPurifier_HTMLModule_TargetNoopener::class,
        HTMLPurifier_HTMLModule_TargetNoreferrer::class,
        HTMLPurifier_HTMLModule_Text::class,
        HTMLPurifier_HTMLModule_Tidy::class,
        HTMLPurifier_HTMLModule_XMLCommonAttributes::class,

        // Injector
        HTMLPurifier_Injector::class,
        HTMLPurifier_Injector_AutoParagraph::class,
        HTMLPurifier_Injector_DisplayLinkURI::class,
        HTMLPurifier_Injector_Linkify::class,
        HTMLPurifier_Injector_PurifierLinkify::class,
        HTMLPurifier_Injector_RemoveEmpty::class,
        HTMLPurifier_Injector_RemoveSpansWithoutAttributes::class,
        HTMLPurifier_Injector_SafeObject::class,

        // Language
        HTMLPurifier_Language::class,

        // Lexer
        HTMLPurifier_Lexer::class,
        HTMLPurifier_Lexer_DirectLex::class,
        HTMLPurifier_Lexer_DOMLex::class,
        HTMLPurifier_Lexer_PH5P::class,

        // Node
        HTMLPurifier_Node::class,
        HTMLPurifier_Node_Comment::class,
        HTMLPurifier_Node_Element::class,
        HTMLPurifier_Node_Text::class,

        // Printer
        HTMLPurifier_Printer::class,
        HTMLPurifier_Printer_ConfigForm::class,
        HTMLPurifier_Printer_CSSDefinition::class,
        HTMLPurifier_Printer_HTMLDefinition::class,

        // Strategy
        HTMLPurifier_Strategy::class,
        HTMLPurifier_Strategy_Composite::class,
        HTMLPurifier_Strategy_Core::class,
        HTMLPurifier_Strategy_FixNesting::class,
        HTMLPurifier_Strategy_MakeWellFormed::class,
        HTMLPurifier_Strategy_RemoveForeignElements::class,
        HTMLPurifier_Strategy_ValidateAttributes::class,

        // TagTransform
        HTMLPurifier_TagTransform::class,
        HTMLPurifier_TagTransform_Font::class,
        HTMLPurifier_TagTransform_Simple::class,

        // Token
        HTMLPurifier_Token::class,
        HTMLPurifier_Token_Comment::class,
        HTMLPurifier_Token_Empty::class,
        HTMLPurifier_Token_End::class,
        HTMLPurifier_Token_Start::class,
        HTMLPurifier_Token_Tag::class,
        HTMLPurifier_Token_Text::class,

        // URIFilter
        HTMLPurifier_URIFilter::class,
        HTMLPurifier_URIFilter_DisableExternal::class,
        HTMLPurifier_URIFilter_DisableExternalResources::class,
        HTMLPurifier_URIFilter_DisableResources::class,
        HTMLPurifier_URIFilter_HostBlacklist::class,
        HTMLPurifier_URIFilter_MakeAbsolute::class,
        HTMLPurifier_URIFilter_Munge::class,
        HTMLPurifier_URIFilter_SafeIframe::class,

        // URIScheme
        HTMLPurifier_URIScheme::class,
        HTMLPurifier_URIScheme_data::class,
        HTMLPurifier_URIScheme_file::class,
        HTMLPurifier_URIScheme_ftp::class,
        HTMLPurifier_URIScheme_http::class,
        HTMLPurifier_URIScheme_https::class,
        HTMLPurifier_URIScheme_mailto::class,
        HTMLPurifier_URIScheme_news::class, 
        HTMLPurifier_URIScheme_nntp::class,
        HTMLPurifier_URIScheme_tel::class,
        
        // VarParser
        HTMLPurifier_VarParser::class,
        HTMLPurifier_VarParser_Flexible::class,
        HTMLPurifier_VarParser_Native::class,

        // <ROOT>
        HTMLPurifier_Arborize::class,
        HTMLPurifier_AttrCollections::class,
        HTMLPurifier_AttrDef::class,
        HTMLPurifier_AttrTransform::class,
        HTMLPurifier_AttrTypes::class,
        HTMLPurifier_AttrValidator::class,
        HTMLPurifier_Bootstrap::class,
        HTMLPurifier_ChildDef::class,
        HTMLPurifier_Config::class,
        HTMLPurifier_ConfigSchema::class,
        HTMLPurifier_ContentSets::class,
        HTMLPurifier_Context::class,
        HTMLPurifier_CSSDefinition::class,
        HTMLPurifier_Definition::class,
        HTMLPurifier_DefinitionCache::class,
        HTMLPurifier_DefinitionCacheFactory::class,
        HTMLPurifier_Doctype::class,
        HTMLPurifier_DoctypeRegistry::class,
        HTMLPurifier_ElementDef::class,
        HTMLPurifier_Encoder::class,
        HTMLPurifier_EntityLookup::class,
        HTMLPurifier_EntityParser::class,
        HTMLPurifier_ErrorCollector::class,
        HTMLPurifier_ErrorStruct::class,
        HTMLPurifier_Exception::class,
        HTMLPurifier_Filter::class,
        HTMLPurifier_Generator::class,
        HTMLPurifier_HTMLDefinition::class,
        HTMLPurifier_HTMLModule::class,
        HTMLPurifier_HTMLModuleManager::class,
        HTMLPurifier_IDAccumulator::class,
        HTMLPurifier_Injector::class,
        HTMLPurifier_Language::class,
        HTMLPurifier_LanguageFactory::class,
        HTMLPurifier_Length::class,
        HTMLPurifier_Lexer::class,
        HTMLPurifier_Node::class,
        HTMLPurifier_PercentEncoder::class,
        HTMLPurifier_Printer::class,
        HTMLPurifier_PropertyList::class,
        HTMLPurifier_PropertyListIterator::class,
        HTMLPurifier_Queue::class,
        HTMLPurifier_Strategy::class,
        HTMLPurifier_StringHash::class,
        HTMLPurifier_TagTransform::class,
        HTMLPurifier_Token::class,
        HTMLPurifier_TokenFactory::class,
        HTMLPurifier_UnitConverter::class,
        HTMLPurifier_URI::class,
        HTMLPurifier_URIDefinition::class,
        HTMLPurifier_URIFilter::class,
        HTMLPurifier_URIParser::class,
        HTMLPurifier_URIScheme::class,
        HTMLPurifier_URISchemeRegistry::class,
        HTMLPurifier_VarParser::class,
        HTMLPurifier_VarParserException::class,
        HTMLPurifier_Zipper::class,

        // StdClass
        stdClass::class
    ];
    // BGWS Modification END

    /**
     * Global configuration object.
     * @type HTMLPurifier_Config
     */
    public $config;

    /**
     * Array of extra filter objects to run on HTML,
     * for backwards compatibility.
     * @type HTMLPurifier_Filter[]
     */
    private $filters = array();

    /**
     * Single instance of HTML Purifier.
     * @type HTMLPurifier
     */
    private static $instance;

    /**
     * @type HTMLPurifier_Strategy_Core
     */
    protected $strategy;

    /**
     * @type HTMLPurifier_Generator
     */
    protected $generator;

    /**
     * Resultant context of last run purification.
     * Is an array of contexts if the last called method was purifyArray().
     * @type HTMLPurifier_Context
     */
    public $context;

    /**
     * Initializes the purifier.
     *
     * @param HTMLPurifier_Config|mixed $config Optional HTMLPurifier_Config object
     *                for all instances of the purifier, if omitted, a default
     *                configuration is supplied (which can be overridden on a
     *                per-use basis).
     *                The parameter can also be any type that
     *                HTMLPurifier_Config::create() supports.
     */
    public function __construct($config = null)
    {
        $this->config = HTMLPurifier_Config::create($config);
        $this->strategy = new HTMLPurifier_Strategy_Core();
    }

    /**
     * Adds a filter to process the output. First come first serve
     *
     * @param HTMLPurifier_Filter $filter HTMLPurifier_Filter object
     */
    public function addFilter($filter)
    {
        trigger_error(
            'HTMLPurifier->addFilter() is deprecated, use configuration directives' .
            ' in the Filter namespace or Filter.Custom',
            E_USER_WARNING
        );
        $this->filters[] = $filter;
    }

    /**
     * Filters an HTML snippet/document to be XSS-free and standards-compliant.
     *
     * @param string $html String of HTML to purify
     * @param HTMLPurifier_Config $config Config object for this operation,
     *                if omitted, defaults to the config object specified during this
     *                object's construction. The parameter can also be any type
     *                that HTMLPurifier_Config::create() supports.
     *
     * @return string Purified HTML
     */
    public function purify($html, $config = null)
    {
        // :TODO: make the config merge in, instead of replace
        $config = $config ? HTMLPurifier_Config::create($config) : $this->config;

        // implementation is partially environment dependant, partially
        // configuration dependant
        $lexer = HTMLPurifier_Lexer::create($config);

        $context = new HTMLPurifier_Context();

        // setup HTML generator
        $this->generator = new HTMLPurifier_Generator($config, $context);
        $context->register('Generator', $this->generator);

        // set up global context variables
        if ($config->get('Core.CollectErrors')) {
            // may get moved out if other facilities use it
            $language_factory = HTMLPurifier_LanguageFactory::instance();
            $language = $language_factory->create($config, $context);
            $context->register('Locale', $language);

            $error_collector = new HTMLPurifier_ErrorCollector($context);
            $context->register('ErrorCollector', $error_collector);
        }

        // setup id_accumulator context, necessary due to the fact that
        // AttrValidator can be called from many places
        $id_accumulator = HTMLPurifier_IDAccumulator::build($config, $context);
        $context->register('IDAccumulator', $id_accumulator);

        $html = HTMLPurifier_Encoder::convertToUTF8($html, $config, $context);

        // setup filters
        $filter_flags = $config->getBatch('Filter');
        $custom_filters = $filter_flags['Custom'];
        unset($filter_flags['Custom']);
        $filters = array();
        foreach ($filter_flags as $filter => $flag) {
            if (!$flag) {
                continue;
            }
            if (strpos($filter, '.') !== false) {
                continue;
            }
            $class = "HTMLPurifier_Filter_$filter";
            $filters[] = new $class;
        }
        foreach ($custom_filters as $filter) {
            // maybe "HTMLPurifier_Filter_$filter", but be consistent with AutoFormat
            $filters[] = $filter;
        }
        $filters = array_merge($filters, $this->filters);
        // maybe prepare(), but later

        for ($i = 0, $filter_size = count($filters); $i < $filter_size; $i++) {
            $html = $filters[$i]->preFilter($html, $config, $context);
        }

        // purified HTML
        $html =
            $this->generator->generateFromTokens(
                // list of tokens
                $this->strategy->execute(
                    // list of un-purified tokens
                    $lexer->tokenizeHTML(
                        // un-purified HTML
                        $html,
                        $config,
                        $context
                    ),
                    $config,
                    $context
                )
            );

        for ($i = $filter_size - 1; $i >= 0; $i--) {
            $html = $filters[$i]->postFilter($html, $config, $context);
        }

        $html = HTMLPurifier_Encoder::convertFromUTF8($html, $config, $context);
        $this->context =& $context;
        return $html;
    }

    /**
     * Filters an array of HTML snippets
     *
     * @param string[] $array_of_html Array of html snippets
     * @param HTMLPurifier_Config $config Optional config object for this operation.
     *                See HTMLPurifier::purify() for more details.
     *
     * @return string[] Array of purified HTML
     */
    public function purifyArray($array_of_html, $config = null)
    {
        $context_array = array();
        $array = array();
        foreach($array_of_html as $key=>$value){
            if (is_array($value)) {
                $array[$key] = $this->purifyArray($value, $config);
            } else {
                $array[$key] = $this->purify($value, $config);
            }
            $context_array[$key] = $this->context;
        }
        $this->context = $context_array;
        return $array;
    }

    /**
     * Singleton for enforcing just one HTML Purifier in your system
     *
     * @param HTMLPurifier|HTMLPurifier_Config $prototype Optional prototype
     *                   HTMLPurifier instance to overload singleton with,
     *                   or HTMLPurifier_Config instance to configure the
     *                   generated version with.
     *
     * @return HTMLPurifier
     */
    public static function instance($prototype = null)
    {
        if (!self::$instance || $prototype) {
            if ($prototype instanceof HTMLPurifier) {
                self::$instance = $prototype;
            } elseif ($prototype) {
                self::$instance = new HTMLPurifier($prototype);
            } else {
                self::$instance = new HTMLPurifier();
            }
        }
        return self::$instance;
    }

    /**
     * Singleton for enforcing just one HTML Purifier in your system
     *
     * @param HTMLPurifier|HTMLPurifier_Config $prototype Optional prototype
     *                   HTMLPurifier instance to overload singleton with,
     *                   or HTMLPurifier_Config instance to configure the
     *                   generated version with.
     *
     * @return HTMLPurifier
     * @note Backwards compatibility, see instance()
     */
    public static function getInstance($prototype = null)
    {
        return HTMLPurifier::instance($prototype);
    }
}

// vim: et sw=4 sts=4
