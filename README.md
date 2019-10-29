This is a image resizer package. I've written this with ZF3 in mind, everything is written with configuration over convention in mind. Highly extendible and easy in use. You can use this along side my other package to optimise images.

Key features of this package is:

* Resizing through CLI
* Resizing through InputFilters
* Resizing through the Service object
* Resizing on the fly (ViewHelper included)

Note: For obvious reasons resizing on the fly is a feature that you need to think twice about using because of the time/performance trade-off. If you are resizing small pictures to begin with than you should be fine.

# Usage

To get started you need to choose one of the programs to resize with. Unlike the optimiser you can only use one resizer with this package. You can configure this globally or happily pass it along.

