# Git Branch Restructuring Guide

## Overview

This guide documents the Git branch strategy for the okapteinis/astra-child repository and provides complete CLI commands to implement the restructuring.

### Current Status

✅ **Documentation Created** - README.md and all supporting docs are in place  
⏳ **Branch Restructuring** - Pending Git CLI execution

### Goal

Restructure branches so that:
- **`nightly`** = Production branch with Supfit customizations (Comet + Atlas)
- **`main`** = Clean archive of upstream brainstormforce/astra-child code
- **`master`** = (To be deleted) Current branch being renamed to `nightly`

---

## Complete Step-by-Step Instructions

### Step 1: Clone the Repository Locally

If you haven't already cloned the repository:

```bash
git clone https://github.com/okapteinis/astra-child.git
cd astra-child
```

### Step 2: Rename master to nightly

```bash
# Ensure you're on the master branch
git checkout master

# Rename the local master branch to nightly
git branch -m master nightly

# Push the new nightly branch to remote
git push origin nightly

# Delete the master branch from remote
git push origin --delete master
```

**Verification:**
```bash
# List all branches
git branch -a

# You should see:
# * nightly
#   remotes/origin/nightly
```

### Step 3: Add Upstream Remote

Add the original Astra Child repository as an upstream remote:

```bash
# Add upstream remote
git remote add upstream https://github.com/brainstormforce/astra-child.git

# Verify it's added
git remote -v

# You should see:
# origin    https://github.com/okapteinis/astra-child.git (fetch)
# origin    https://github.com/okapteinis/astra-child.git (push)
# upstream  https://github.com/brainstormforce/astra-child.git (fetch)
# upstream  https://github.com/brainstormforce/astra-child.git (push)
```

### Step 4: Fetch Upstream Code

```bash
# Fetch all branches from upstream
git fetch upstream

# Verify upstream branches are fetched
git branch -a

# You should see remotes/upstream/master and potentially remotes/upstream/main
```

### Step 5: Create main Branch from Upstream

The original Astra repository uses `master` as the default branch.

```bash
# Create local main branch from upstream/master
git checkout -b main upstream/master

# Push main to remote
git push origin main

# Switch back to nightly for continued development
git checkout nightly
```

**Alternative:** If upstream uses `main` instead of `master`:

```bash
# Create local main branch from upstream/main
git checkout -b main upstream/main

# Push main to remote
git push origin main

# Switch back to nightly
git checkout nightly
```

### Step 6: Set Default Branch on GitHub

Go to the GitHub repository settings and change the default branch:

**Via Web Interface:**
1. Go to https://github.com/okapteinis/astra-child/settings
2. Click **Branches** in the left sidebar (under "Code and automation")
3. Under "Default branch", change from `master` to `nightly`
4. Click the update button

**Or, use GitHub CLI:**

```bash
# If you have GitHub CLI installed
gh repo edit --default-branch nightly
```

### Step 7: Verify the Setup

```bash
# List all branches
git branch -a

# Expected output:
# * nightly
#   main
#   remotes/origin/main
#   remotes/origin/nightly
#   remotes/upstream/master
#   (and other upstream branches)

# Verify branch contents
git log -1 --oneline nightly  # Should show recent Supfit commits
git log -1 --oneline main      # Should show upstream Astra commits
```

### Step 8: Protect Branches (Optional)

To prevent accidental deletion or force pushes:

**Via GitHub Web Interface:**
1. Go to https://github.com/okapteinis/astra-child/settings/branches
2. Click **Add rule**
3. Apply rules to both `main` and `nightly`:
   - Require pull request reviews
   - Require status checks to pass
   - Require branches to be up to date
   - Restrict who can push to matching branches

---

## Important Notes

### Branch Purposes

**`nightly` Branch:**
- Contains all Supfit customizations
- Includes Comet Assistant integration
- Includes ChatGPT Atlas optimizations
- **This is the deployment branch for production**
- Actively developed and updated

**`main` Branch:**
- Clean snapshot of brainstormforce/astra-child
- Used as reference for original code
- Useful for comparing what's been customized
- Rarely changed (only when syncing with upstream)
- **Do NOT deploy this branch**

### Workflow After Setup

```bash
# Always work on nightly
git checkout nightly

# Make changes
# ...

# Commit
git add .
git commit -m "Add feature: description"

# Push to nightly
git push origin nightly

# Deploy from nightly
cd /path/to/wp-content/themes/astra-child
git pull origin nightly
```

### Syncing with Upstream Updates

If Astra releases a new version:

```bash
# Update main branch from upstream
git checkout main
git pull upstream master  # or upstream/main if applicable

# Review changes
git log main..nightly --oneline  # See what's different

# If you want to merge upstream updates into nightly (optional)
git checkout nightly
git merge main

# Resolve any conflicts if necessary
# Test thoroughly before pushing
git push origin nightly
```

---

## Troubleshooting

### "Branch already exists" error

```bash
# If nightly already exists locally, delete it first
git branch -D nightly

# Then proceed with renaming
git branch -m master nightly
```

### "Permission denied" when pushing

```bash
# Ensure you have push access
# Check GitHub authentication
git remote set-url origin git@github.com:okapteinis/astra-child.git

# Or use HTTPS with PAT (Personal Access Token)
git remote set-url origin https://github.com/okapteinis/astra-child.git
```

### Merge conflicts during upstream sync

```bash
# Abort current merge
git merge --abort

# Manually examine differences
git diff main..nightly -- [specific-file]

# Resolve conflicts in affected files
# Then continue
git add .
git commit -m "Resolve merge conflicts"
```

### Accidentally deleted a branch

```bash
# Recover from reflog
git reflog

# Find the commit where branch was
# Recreate branch
git checkout -b recovered-branch [commit-hash]
```

---

## Quick Reference

### Essential Commands

```bash
# View current branch
git branch

# Switch branches
git checkout nightly
git checkout main

# Create feature branch
git checkout -b feature/my-feature nightly

# Push all branches
git push origin --all

# Fetch all updates
git fetch --all

# See remote branches
git branch -r

# Delete local branch
git branch -d branch-name

# Delete remote branch
git push origin --delete branch-name
```

### Viewing Branch History

```bash
# See commits on nightly
git log nightly --oneline -10

# See commits on main
git log main --oneline -10

# See differences between branches
git diff main..nightly --stat

# See commits unique to nightly
git log main..nightly --oneline
```

---

## Deployment Instructions

After branch restructuring is complete, deployment commands remain the same:

```bash
# On Hetzner VPS
cd /var/www/wordpress/wp-content/themes/astra-child

# Ensure on nightly branch
git checkout nightly

# Pull latest changes
git pull origin nightly

# If using WordPress caching, clear cache
wp cache flush

# Restart services if necessary
sudo systemctl restart php7.4-fpm
sudo systemctl restart nginx
```

---

## Verification Checklist

After completing all steps, verify:

- [ ] `nightly` branch exists locally and on GitHub
- [ ] `main` branch exists locally and on GitHub
- [ ] `master` branch is deleted from GitHub
- [ ] Default branch on GitHub is set to `nightly`
- [ ] Upstream remote is configured
- [ ] README.md documents the branch strategy
- [ ] All commits are preserved in `nightly`
- [ ] `main` contains clean upstream code
- [ ] Deployments continue to use `nightly`

---

## References

- [Git Branch Management](https://git-scm.com/book/en/v2/Git-Branching-Branch-Management)
- [GitHub CLI Reference](https://cli.github.com/manual/)
- [Syncing a Fork](https://docs.github.com/en/pull-requests/collaborating-with-pull-requests/working-with-forks/syncing-a-fork)
- [Renaming Branches](https://docs.github.com/en/repositories/configuring-branches-and-merges-in-your-repository/managing-branches-in-your-repository/renaming-a-branch)

---

**Status:** 📋 Ready for implementation  
**Last Updated:** January 2026  
**Applicable to:** okapteinis/astra-child on GitHub
