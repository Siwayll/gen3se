%skip       space       \s

%token  scenari         Scenario  -> sce
%token  sce:tab         [ ]{2}
%token  sce:space       [ ]
%token  sce:eol         [\n\r]+
%token  sce:end         [-]{3} -> default
%token  sce:choice      >
%token  sce:name        [a-zéêèâàôîïöäë][a-zA-ZéêèâàôîïöäëùÉÊÈÂÀÔÎÏÖÄËÙ]*
%token  sce:_string     "  -> string


%token  choice           Choix -> chce
%token  chce:tab         [ ]{2}
%token  chce:space       [ ]
%token  chce:eol         [\n\r]+
%token  chce:tagCleat    #
%token  chce:globalCleat    \*
%token  chce:tagName     [A-Z_&!]+

%token  chce:bracket_    \[
%token  chce:_bracket    \]
%token  chce:rBracket_    \(
%token  chce:_rBracket    \)
%token  chce:colon       :
%token  chce:null                ~
%token  chce:storageDelimiter    \.
%token  chce:end         [-]{3} -> default
%token  chce:name        [a-zéêèâàôîïöäë][a-zA-ZéêèâàôîïöäëùÉÊÈÂÀÔÎÏÖÄËÙ]*
%token  chce:integer     (0|[1-9]\d*)
%token  chce:string      ([^"]+)
%token  chce:_string     "  -> string

%token  string:value     ([^"]+)
%token  string:string_   " -> __shift__



#root:
    scenario()? choice()*

name:
    <name>

tagName:
    <tagName>

weight:
    <integer>

null:
    <null>

stringQuote:
    ::_string:: <value>? ::string_::



#scenario:
    ::scenari:: ::space:: name() ::eol::
    (scenarioChoice())+
    (scenarioRender())?
    ::end::

#scenarioChoice:
    ::tab:: ::choice:: ::space:: name() ::eol::

#scenarioRender:
    stringQuote() ::eol::




#choice:
  ::choice:: ::space:: name() choiceStorage()? ::eol::
  ( choiceGlobalElement() )*
  ( choiceOption() )+
  ::end::

choiceGlobalElement:
    ::tab:: ::tab:: ::globalCleat:: choiceElement() ::eol::

#choiceStorage:
    ::space:: ::bracket_:: name() ( ::storageDelimiter:: name())* ::_bracket::

#choiceOption:
    ::tab:: ( name() ::space:: )? ( weight() ::space:: )? choiceMainValue() ( ::space:: choiceTag() )*
    ( ::eol:: ::tab:: ::tab:: choiceElement() )*
    ::eol::

#choiceMainValue:
    (choiceDataName()  ::colon:: )? choiceDataValue()

#choiceElement:
    choiceData()

#choiceTag:
    ::tagCleat:: tagName() ::rBracket_:: weight() ::_rBracket::

choiceData:
    choiceDataName() ::colon:: choiceDataValue()

choiceDataName:
    stringQuote() | name()

choiceDataValue:
    stringQuote() | null()

