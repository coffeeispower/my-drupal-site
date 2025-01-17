{
  description = "Development shell with ddev";

  inputs = {
    nixpkgs.url = "github:coffeeispower/nixpkgs/fix-buildComposerProject";
    # nixpkgs.url = "path:/home/tiago/Projects/nixpkgs";
    flake-utils.url = "github:numtide/flake-utils";
  };

  outputs = { self, nixpkgs, flake-utils }: flake-utils.lib.eachDefaultSystem (system:
  let pkgs = import nixpkgs { inherit system; }; in {
    devShell = pkgs.mkShell {
      buildInputs = [
        pkgs.ddev
        pkgs.php84
        pkgs.php84Packages.composer
        pkgs.flyctl
      ];
    };
    packages.default = pkgs.php84.buildComposerProject2 (finalAttrs: {
      pname = "my-drupal-site";
      version = "1.0.0";
      src = ./.;
      vendorHash = "sha256-6G8n9R//7Gg4Q4+C/IWAYSkYfg0fFjGv8+PthPVjmkY=";
      composerLock = ./composer.lock;
      composerNoPlugins = false;
      composerNoScripts = false;
    });
  });
}
