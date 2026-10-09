# Cents

> [!TIP]
> This template is a starting point you can use for every PHP package. We offer:
>
> - Clear structure and a small working example
> - The PHP version in [.php-version](.php-version), shared by local development and CI
> - PHPUnit, [PSR-12](https://www.php-fig.org/psr/psr-12/), and PHPStan in continuous integration
> - Automated releases with [Release Please](.github/workflows/release.yml) and SLSA provenance attestation
>
> A Moodle plugin belongs in the [Moodle plugin template](https://github.com/fulldecent/moodle-local_plugin_template), not here.
>
> What is in-scope for this template?
>
> We the people who publish PHP packages, in order to advocate for a safer installation path and great defaults for PHP, maintain this starting point for all projects.
>
> This php-package-template must remain broad—addressing the needs of many kinds of packages. Every project deserves a README, and a clear rule on basic formatting questions, this is why we include continuous integration linting.
>
> We do not specify that GitHub and GitHub Actions are the only way to host projects, others may consider our GitHub-specific notes as a starting point guide for implementing outside of GitHub.
>
> And now below is the template, shown for a specific hypothetical project, enjoy!

[![Build and test](https://github.com/fulldecent/php-package-template/actions/workflows/build-test.yml/badge.svg?branch=main)](https://github.com/fulldecent/php-package-template/actions/workflows/build-test.yml) [![Lint](https://github.com/fulldecent/php-package-template/actions/workflows/lint.yml/badge.svg?branch=main)](https://github.com/fulldecent/php-package-template/actions/workflows/lint.yml)

Add and subtract money. Keep every amount as integer cents.

Cents reads two decimal amounts and `+` or `-`. It returns the result as a decimal amount. `0.10 + 0.20` is `0.30`.

```console
$ cents 1.50 + 0.75
2.25

$ cents 0.10 + 0.20
0.30
```

> [!NOTE]
> Replace the project name, description, demonstration and badge URLs with your own. Show what your project does before asking people to read further.

## Installation

You will need Git, PHP at the version in [.php-version](.php-version), Composer 2, and the PHP extensions `dom`, `mbstring`, `tokenizer`, `xml` and `xmlwriter`. Open a terminal (PowerShell on Windows) and use the instructions for your operating system.

The PHP version below is 8.5 because that is what [.php-version](.php-version) says. When that file changes, change these commands with it. CI installs that same version with [shivammathur/setup-php](https://github.com/shivammathur/setup-php). A distribution package named `php` that ignores the file is the wrong PHP.

> [!WARNING]
> Do not install PHP or Composer by piping a downloaded script into a shell. The commands below use a package manager, or a named archive for the current PHP packages.

### Linux

On Ubuntu 22.04+ or Debian 12+, install PHP 8.5 from the [Ondřej Surý PHP archive](https://launchpad.net/~ondrej/+archive/ubuntu/php):

```sh
sudo apt update
sudo apt install -y ca-certificates software-properties-common
sudo add-apt-repository -y ppa:ondrej/php
sudo apt update
sudo apt install -y git php8.5-cli php8.5-xml php8.5-mbstring php8.5-zip unzip composer
sudo update-alternatives --set php /usr/bin/php8.5
```

`php8.5-xml` provides `dom`, `tokenizer`, `xml` and `xmlwriter`.

On Fedora, install PHP 8.5 from the [Remi repository](https://rpms.remirepo.net/wizard/):

```sh
sudo dnf install -y https://rpms.remirepo.net/fedora/remi-release-$(rpm -E %fedora).rpm
sudo dnf module reset -y php
sudo dnf module enable -y php:remi-8.5
sudo dnf install -y git php-cli php-xml php-mbstring php-zip composer
```

### macOS

Install Apple's Command Line Tools if they are not already installed:

```sh
xcode-select --install
```

Complete the installation dialog. With Homebrew installed, install Git, PHP 8.5 and Composer:

```sh
brew install git php@8.5 composer
brew link --overwrite --force php@8.5
```

[php@8.5](https://formulae.brew.sh/formula/php) stays on PHP 8.5. Homebrew's unversioned `php` formula moves to the next release on its own, so it will not keep matching [.php-version](.php-version). `brew link` is what puts that `php` on your `PATH`.

### Windows

Use winget to install Git, the non-thread-safe PHP 8.5 build (the CLI build) and Composer. Open a new PowerShell window after installation so `PATH` is current.

```powershell
winget install --exact --id Git.Git
winget install --exact --id PHP.PHP.NTS.8.5
winget install --exact --id Composer.Composer
```

### Build and install

Clone the project and install dependencies. `composer install` uses [composer.lock](composer.lock).

```sh
git clone https://github.com/fulldecent/php-package-template.git
cd php-package-template
php --version
composer --version
composer install --no-interaction --prefer-dist
```

Run the command without a global install:

```sh
php bin/cents 1.50 + 0.75
php bin/cents 0.10 + 0.20
```

`composer install` puts the `cents` command on `vendor/bin/cents` as well.

> [!NOTE]
> Explain what your users need to install, including the tools your project is built on. Replace the repository URL and command name with your own.

## Usage

Evaluate one addition or one subtraction. Each amount is 1 to 12 digits, a dot, and exactly two digits, such as `0.10` or `1000.00`. The operator is `+` or `-`. Inputs are non-negative. The result may be negative.

```sh
php bin/cents 1.50 + 0.75
php bin/cents 1.00 - 1.50
```

From another project that requires this package:

```php
use FullDecent\Cents\Cents;

Cents::evaluate('0.10 + 0.20'); // "0.30"
```

The command joins its arguments with spaces. Input that uses any other character, that is not exactly two amounts and one operator, or that uses a leading zero (`01.00`), throws `InvalidArgumentException`. The command writes that message to standard error and exits with status 1.

Amounts stay integers. The library never converts them with a floating-point number, so `0.10 + 0.20` is `0.30` rather than `0.30000000000000004`.

> [!NOTE]
> Explain how to use your project, including the limits that matter to users.

## Development

Thank you for taking an interest in improving Cents and the programs of people using it!

Follow the installation instructions above to get Git, PHP 8.5 and Composer. Work from the project directory. The implementation is in [src/Cents.php](src/Cents.php) and [bin/cents](bin/cents). You can run it without a global install:

```sh
php bin/cents 1.50 + 0.75
```

Commit [composer.lock](composer.lock) so the development tools stay reproducible. The published archive omits that file. People who require the library resolve their own dependencies from [composer.json](composer.json).

There is no `version` field in [composer.json](composer.json). Packagist and the release workflow take the version from the git tag. A `version` field in the manifest fights the tag.

### Testing

All project updates that we release must conform to our test suite. GitHub Actions runs [checks](./.github/workflows) on pushes to `main` and pull requests. You can also run them locally before sending proposed changes:

```sh
composer test
composer check
```

`composer test` runs PHPUnit. `composer check` runs PHP-CS-Fixer in dry-run mode and PHPStan at level 9. The tests in [tests/CentsTest.php](tests/CentsTest.php) check addition, subtraction below zero, rejected input, and the `0.10 + 0.20` case. [tests/CliTest.php](tests/CliTest.php) checks the exit status, standard output and standard error of the actual program.

Apply PHP-CS-Fixer when it reports a diff:

```sh
vendor/bin/php-cs-fixer fix
```

With an actively maintained version of Node.js installed, correct other formatting issues before sending proposed changes:

```sh
npx prettier@latest --check . --write
npx markdownlint-cli@latest "**/*.md" --fix
```

Composer puts install outputs in the ignored `vendor/` directory. Packed releases go in the ignored `dist/` directory.

### Releases

Use `fix:`, `feat:` or `BREAKING CHANGE:` in your commit messages. This triggers our bot to make a release draft pull request. Merging that pull request triggers a new tag and GitHub Release.

The [release workflow](.github/workflows/release.yml) uses Release Please's `simple` release type. It does not need a release-please config file. Do not add a `version` field to [composer.json](composer.json) in the release pull request. The tag is the version.

A commit on `main` whose message starts with `fix:` opens or updates a release pull request that bumps the patch version. `feat:` bumps the minor version. `BREAKING CHANGE:` bumps the major version. One release pull request collects every such commit since the last `v` tag. A commit with any other prefix, including `chore:` and `docs:`, does not open that pull request. Merging the release pull request is the release. Release Please writes [CHANGELOG.md](CHANGELOG.md) in that pull request and does not create the tag. The publish job creates the tag.

[Build and test](.github/workflows/build-test.yml) installs dependencies, checks style and types, runs tests, packs the zip, then attests and uploads it. The zip name comes from the Composer package name, the part after the slash. This package is `fulldecent/cents`, so the release includes `cents.zip` and `release.sigstore.jsonl`, containing build provenance and version attestations. The zip filename has no version in it. The version is the tag, and the attestation records that version without a leading `v`.

Packagist installs this library from the git tag. Submit the repository once at [packagist.org](https://packagist.org/packages/submit) and connect the GitHub hook ([how to update packages](https://packagist.org/about#how-to-update-packages)). Each tag the publish job pushes then becomes a Packagist version. `composer require fulldecent/cents` installs that version. The workflow does not send a Packagist token. Packagist does not serve the attested zip. The zip is the GitHub Release asset.

### An existing package

Copy the workflows in [.github/workflows](.github/workflows). Leave your package name, source, and `php` constraint alone. Set [.php-version](.php-version) to a PHP release that satisfies that constraint. This template pins 8.5 because Cents requires 8.5. CI installs the version in that file.

If you run Composer on a newer PHP than that constraint, set `config.platform.php` to the newest patch of the minimum version, such as `8.3.99`. Otherwise `composer update` can lock a dependency that requires the newer PHP, and CI fails when it installs the version in `.php-version`.

Release Please continues from the latest tag shaped like `v1.2.3`. Composer and Packagist treat that tag as version 1.2.3. A tag shaped like `1.2.3` does not count, and Release Please would start again at 1.0.0. Point a `v1.2.3` tag at the commit your latest version already uses. From a clone that has the old tag:

```sh
git tag v1.2.3 "1.2.3^{}"
git push origin v1.2.3
```

`1.2.3^{}` is that tag's commit. The new tag is the same commit, which is the version Packagist already publishes. The next `fix:` or `feat:` commit after that tag opens the release pull request.

> [!NOTE]
> In your GitHub repository settings, under Actions, General, Workflow permissions, select read and write permissions and check "Allow GitHub Actions to create and approve pull requests". Under General, Releases, enable release immutability. Attestations are available for public repositories; private repositories require GitHub Enterprise Cloud.
>
> A repository created from this template starts with no tags and no releases. Release Please reads the latest tag on the default branch to choose the next version. A repository with no tag gets a first release pull request for 1.0.0. The publish job accepts a tag shaped like `v1.2.3`.
>
> Run these commands from a clone of the new repository. `gh` fills in `{owner}/{repo}` from that clone.
>
> List tags:
>
> ```sh
> gh api repos/{owner}/{repo}/tags --jq '.[].name'
> ```
>
> Set the starting tag on the current `main` commit. `v0.0.0` is the version Release Please counts forward from. Use another `vMAJOR.MINOR.PATCH` tag when this repository should start later.
>
> ```sh
> gh api --method POST repos/{owner}/{repo}/git/refs \
>   -f ref="refs/tags/v0.0.0" \
>   -f sha="$(gh api repos/{owner}/{repo}/commits/main --jq .sha)"
> ```
>
> `gh release list` and `gh release create` publish the releases this workflow creates after that tag.

### Maintenance

The project administrator completes these maintenance tasks each month. If they are 3+ months late, please remind them or send your own issue/pull request.

1. Identify external Actions in [.github/workflows](.github/workflows) and look for available new versions. Review and update them if it is safe. GitHub-supported Actions (under the actions/ organization) may require only cursory review. [shivammathur/setup-php](https://github.com/shivammathur/setup-php) and [googleapis/release-please-action](https://github.com/googleapis/release-please-action) are not in that organization, so read their changelogs.
1. Review the PHP version in [.php-version](.php-version) and the `php` constraint in [composer.json](composer.json). Update the installation instructions when that version changes. `8.5` means the newest PHP 8.5 patch.
1. Review direct dependencies with `composer outdated --direct`.

## Project scope

We are people who move money in PHP. The language's floating-point numbers cannot represent `0.10` exactly, and adding two of them is a bad way to get `0.30`.

Cents does that one job: add or subtract two amounts written with two decimal places, using integer cents only. It keeps the package empty of runtime dependencies.

We specifically will not add multiply, divide, rounding, currency codes, thousands separators or a graphical interface.

> [!NOTE]
> Introduce your community, explain what is in scope and say what is out of scope. Help people recognize when their own work belongs here.

## References

1. We use title case only for proper nouns, including the name of our project.
1. PHP documents that floating-point numbers cannot represent every decimal fraction, and shows `0.1 + 0.2` as the case to avoid. [Floating point numbers](https://www.php.net/manual/en/language.types.float.php)
1. [PSR-12](https://www.php-fig.org/psr/psr-12/) is why `*.php` uses four spaces in [.editorconfig](.editorconfig). Other files do not set an indent size.
1. [composer.json `version`](https://getcomposer.org/doc/04-schema.md#version) is omitted. The git tag is the version. [composer.lock](composer.lock) is committed so CI is reproducible ([Composer: commit your lock file](https://getcomposer.org/doc/01-basic-usage.md#commit-your-composer-lock-file-to-version-control)); a library is allowed to leave it uncommitted ([Composer: lock file for libraries](https://getcomposer.org/doc/02-libraries.md#lock-file)), and the published archive does leave it out.
1. This project is built based on [best practices documented in php-package-template](https://github.com/fulldecent/php-package-template/), release 1.0.0.
1. The floor for the README, the lint workflow and the release workflow comes from [project-template](https://github.com/fulldecent/project-template), release 1.3.0. The release attestation shape comes from [node.js-template](https://github.com/fulldecent/node.js-template/), release 1.0.0.
1. The Composer ignore rules in [.gitignore](.gitignore) come from [GitHub's Composer gitignore](https://github.com/github/gitignore/blob/main/Composer.gitignore).
1. This project is released under the [MIT license](LICENSE.md).

> [!NOTE]
> Carefully consider which license to apply to your project and replace the copyright line in [LICENSE.md](LICENSE.md). Cite external sources that materially informed your decisions, including the release of this PHP package template you used.
