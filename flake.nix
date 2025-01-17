{
  description = "Development shell with ddev";

  inputs = {
    nixpkgs.url = "github:NixOS/nixpkgs/nixos-unstable";
    flake-utils.url = "github:numtide/flake-utils";
  };

  outputs = { self, nixpkgs, flake-utils }: flake-utils.lib.eachDefaultSystem (system:
  let pkgs = import nixpkgs { inherit system; }; in {
    devShell = pkgs.mkShell {
      buildInputs = [
        pkgs.ddev
        pkgs.php
        pkgs.flyctl
      ];
    };
    packages.default = pkgs.php.buildComposerProject2 (finalAttrs: {
      pname = "my-drupal-site";
      version = "1.0.0";
      src = ./.;
      vendorHash = "sha256-m6+qTq489RgVROeYYZxwHNbdB94uPDCTf+b4HPMIaTk=";
      composerLock = ./composer.lock;
      composerNoPlugins = false;
      composerNoScripts = false;
    });
  });
}
