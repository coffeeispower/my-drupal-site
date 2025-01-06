{
  description = "Development shell with ddev";

  inputs = {
    nixpkgs.url = "github:NixOS/nixpkgs/nixos-unstable";
    flake-utils.url = "github:numtide/flake-utils";
  };

  outputs = { self, nixpkgs, flake-utils }: flake-utils.lib.eachDefaultSystem (system: {
    devShell = (import nixpkgs {
      inherit system;
    }).mkShell {
      buildInputs = [
        (import nixpkgs { inherit system; }).ddev
      ];
    };
  });
}
