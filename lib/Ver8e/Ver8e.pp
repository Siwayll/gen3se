%skip       space       \s
%skip       comment     //([^\n\r])*

%token  scenari                  Scenario  -> sce
%token  sce:spaceComa            [ \n\r]*,[ \n\r]*
%token  sce:tab                  [ ]{2}
%token  sce:space                [ ]
%token  sce:eol                  [\n\r]+
%token  sce:end                  [-]{3} -> default
%token  sce:choice               >
%token  sce:multiplicator        \*
%token  sce:integer              (\d+)
%token  sce:modCleat             mod:[ ]?
%token  sce:modName              [A-Z][A-za-z]*
%token  sce:name                 [a-zéêèâàôîïöäë][a-zA-ZéêèâàôîïöäëùÉÊÈÂÀÔÎÏÖÄËÙ0-9]*
%token  sce:_string              "  -> string


%token  choice                   Choix -> chce
%token  chce:tab                 [ ]{2}
%token  chce:space               [ ]
%token  chce:eol                 [\n\r]+
%token  chce:adder               > -> chceAdd
%token  chce:tagAdder            \+# -> chceAddTag
%token  chce:tagCleat            # -> tag
%token  chce:globalCleat         \*
%token  chce:bracket_            \[ -> storage
%token  chce:rBracket_           \(
%token  chce:_rBracket           \)
%token  chce:colon               :
%token  chce:null                ~

%token  chce:end                 [-]{3} -> default
%token  chce:name                [a-zéêèâàôîïöäë][a-zA-ZéêèâàôîïöäëùÉÊÈÂÀÔÎÏÖÄËÙ0-9]*
%token  chce:integer             (0|[1-9]\d*)
%token  chce:string              ([^"]+)
%token  chce:_string             "  -> string

%token  string:value             ([^"]+)
%token  string:string_           " -> __shift__

%token  chceAddTag:tagName       [A-Z_&!]+ -> chce

%token  storage:_bracket            \] -> chce
%token  storage:name                [a-zA-ZéêèâàôîïöäëùÉÊÈÂÀÔÎÏÖÄËÙ0-9\-]+
%token  storage:storageDelimiter    \.

%token  chceAdd:atEnd            >
%token  chceAdd:data             \+
%token  chceAdd:dataConcat       \.
%token  chceAdd:dataMustache     \{\}
%token  chceAdd:atEnd            >
%token  chceAdd:multiplicator    \*
%token  chceAdd:integer          (\d+)
%token  chceAdd:space            [ ]
%token  chceAdd:name             [a-zéêèâàôîïöäë][a-zA-ZéêèâàôîïöäëùÉÊÈÂÀÔÎÏÖÄËÙ0-9]* -> chce

%token  tag:tagName             [A-Z_&!]+
%token  tag:rBracket_           \(
%token  tag:_rBracket           \) -> __shift__
%token  tag:tagWeight           [+-]?(0|[1-9]\d*)


#root:
    scenario()? choice()*

name:
    <name>

tagName:
    <tagName>

weight:
    <integer>

tagValue:
    <tagWeight>

null:
    <null>

stringQuote:
    ::_string:: <value>? ::string_::

//                                     _
//    _____________  ____  ____ ______(_)___
//   / ___/ ___/ _ \/ __ \/ __ `/ ___/ / __ \
//  (__  ) /__/  __/ / / / /_/ / /  / / /_/ /
// /____/\___/\___/_/ /_/\__,_/_/  /_/\____/
#scenario:
    ::scenari:: ::space:: name() ::eol::
    ( scenarioMod() )?
    ( scenarioChoice() )+
    ( scenarioRender() )?
    ::end::

#scenarioChoice:
    ::tab:: ::choice:: scenarioChoiceMultiplicator() ::space:: name() ::eol::

#scenarioChoiceMultiplicator:
    (::space:: ::multiplicator:: weight())?

#scenarioMod:
    ::tab:: ::modCleat:: ( <modName> ( ::spaceComa:: | ::eol:: ) )+

#scenarioRender:
    stringQuote() ::eol::



//         __          _
//   _____/ /_  ____  (_)_______
//  / ___/ __ \/ __ \/ / ___/ _ \
// / /__/ / / / /_/ / / /__/  __/
// \___/_/ /_/\____/_/\___/\___/
#choice:
  ::choice:: ::space:: name() choiceStorage()? ::eol::
  ( choiceRules() )*
  ( choiceGlobalElement() )*
  ( choiceOption() )+
  ::end::

choiceGlobalElement:
    ::tab:: ::tab:: ::globalCleat:: choiceElement() ::eol::

#choiceRules:
    ::tab:: ::tab:: choiceElement() ::eol::

#choiceStorage:
    ::space:: ::bracket_:: name() ( ::storageDelimiter:: name())* ::_bracket::

#choiceOption:
    ::tab:: ( name() ::space:: )? ( weight() ::space:: )? choiceMainValue() ( ::space:: choiceTag() )*
    ( ::eol:: ::tab:: ::tab:: choiceElement() )*
    ( ::eol:: ::tab:: ::tab:: ::tagAdder:: tagAdder() )*
    ( ::eol:: ::tab:: ::tab:: ::adder:: addChoiceElement() )*
    ::eol::

#choiceMainValue:
    (choiceDataName()  ::colon:: )? choiceDataValue()

#choiceElement:
    choiceData()

#choiceTag:
    ::tagCleat:: tagName() ::rBracket_:: tagValue() ::_rBracket::

choiceData:
    choiceDataName() ::colon:: choiceDataValue()

choiceDataName:
    stringQuote() | name()

choiceDataValue:
    stringQuote() | null()

//   ___ _        _          _      _    _
//  / __| |_  ___(_)__ ___  /_\  __| |__| |___ _ _
// | (__| ' \/ _ \ / _/ -_)/ _ \/ _` / _` / -_) '_|
//  \___|_||_\___/_\__\___/_/ \_\__,_\__,_\___|_|
//
#addChoiceIndicator:
    (<atEnd> | <dataMustache> | <data> | <dataConcat>)?

#addChoiceMultiplicator:
    (::space:: ::multiplicator:: weight())?

#addChoiceElement:
    addChoiceIndicator() addChoiceMultiplicator() ::space:: name()

#tagAdder:
    tagName()
