# phpBB 3.1 Extension - hanleyp fancydice

## Installation

Clone into phpBB/ext/hanelyp/fancydice:

Go to "ACP" > "Customise" > "Extensions" and enable the extension.

## dice spec language

dice spec examples
- 3d6			Roll 3 6-sided dice
- 1d20+12		Roll 1 20-sided die and add 12
The dice spec cannot have spaces in it.	You can roll multiple specs
at once by separating them with spaces.
The spec consists of the following tokens:

- {number}	A literal number
  - Example:	13

- {string}	Text enclosed in quotes
  - A string retains independent identity across adds, not supported for -, *, and /
  - Example:	"**"

- {alpha}		A word containing only letters is a macro, which can be a full or partial dice spec.
  - Example:		fire
  - Resolves to:	1d4+1

- (spec)		A spec inside parenthesis will be evaluated to a single number before other tokens are evaluated.
  - Example:		(1,2)
  - Resolves to:	3

- _		This will evaluate to a list of numbers, from the preceding to the following, inclusive.
  - Example:		1_5
  - Resolves to:	1,2,3,4,5

- [spec]		A token will be chosen at random from within this spec.
  - Example:		[1,2,3]
  - Resolves to:	one of:	1, 2, 3

- @		Repeats the following token a number of times equal to the previous token.	following token should be {number}, (spec), or [spec].
  - Example:		3@5
  - Resolves to:	5,5,5

- + - These only effect the sign of the following number.
  - Examples1:		+3	Example2:	-5
  - Resolves to:	3				-5

- * /		These perform multiplication and division.
  - Example1:		6*2	Example2:	9/3
  - Resolves to:	12				3

- \>		This may only be used in macros, and lets the macro pull in the following token.
  - Example:	The macro 'd' is defined as '@[1_>]'
  - so 3d8 resolves to 3@[1_8], which resolves to
  - [1_8][1_8][1_8] which might resolve to 3,7,4.

- Other	Any other symbol is discarded,	but serves to separate tokens
  - Example:		3,2
  - Resolves as:	3,2
  - The comma is discarded, but prevents it from resolving as 32.

## License

[GPLv2](license.txt)
