# AGENTS.md

## Scope
This agent manages Architecture Decision Records (ADR), architectural documentation, and general development activities.

## Agent behavior
- Before writing: propose a concise plan (max 10 steps)
- Do not refactor unless explicitly requested
- Ask for confirmation before running shell commands

## Git
- Always generate commit messages in English
- Always use the [Conventional Commits](https://www.conventionalcommits.org/en/v1.0.0/) standard
- Show the generated commit message before committing
- Commit only files that are already staged in git
- Add files to the git stage only when explicitly instructed

## Project
- The project is open source and hosted on GitHub
- The codebase is written in PHP
- The code is documented using PHPDoc
- The code is tested with PHPUnit
- The project is maintained with Composer

## Canonical paths
- ADR: docs/decisions/
- ADR template: docs/decisions/_templates/adr-template.md
- Documentation: docs/documentation/
- Structurizr: docs/workspace.dsl

## ADR rules (MANDATORY)
- Each ADR is a Markdown file located in `docs/decisions/`
- Naming convention: NNNN-kebab-case.md
- The content MUST strictly follow the official template
- NEVER modify the template
- Always fill in:
  - Title
  - Status
  - Date
  - Context and problem statement
  - Decision drivers
  - Considered options
  - Decision outcome

## Documentation rules
- If a decision is architecturally relevant:
  - it must be referenced in `docs/documentation/`
- Do not restructure existing files unless explicitly requested
