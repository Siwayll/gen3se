%skip       space       \s

%token  scenari         Scenario  -> sce
%token  sce:tab         [ ]{2}
%token  sce:space       [ ]
%token  sce:eol         [\n]
%token  sce:end         [-]{3} -> default
%token  sce:choice      >
%token  sce:name        [a-zéêèâàôîïöäë][a-zA-ZéêèâàôîïöäëùÉÊÈÂÀÔÎÏÖÄËÙ]*
%token  sce:_string     "  -> string


%token  choice           Choix -> chce
%token  chce:tab         [ ]{2}
%token  chce:space       [ ]
%token  chce:eol         [\n]+

%token  chce:bracket_    \[
%token  chce:_bracket    \]
%token  chce:colon       :
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

weight:
    <integer>

stringQuote:
    ::_string:: <value> ::string_::



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
  (choiceOption())+
  ::end::

#choiceOption:
    ::tab:: ( name() ::space:: )? ( weight() ::space:: )? choiceMainValue()
    ( ::eol:: ::tab:: ::tab:: choiceElement() )*
    ::eol::

#choiceMainValue:
    (choiceDataName()  ::colon:: )? choiceDataValue()

#choiceElement:
    choiceData()

choiceSound
    ::bracket_:: choiceDataValue() ::_bracket::

choiceData:
    choiceDataName() ::colon:: choiceDataValue()

choiceDataName:
    stringQuote() | name()

choiceDataValue:
    stringQuote()

#choiceStorage:
    ::space:: ::bracket_:: name() ( ::storageDelimiter:: name())* ::_bracket::


